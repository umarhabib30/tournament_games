<?php

namespace App\Http\Controllers;

use App\Models\Result;
use App\Models\Tournament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ResultController extends Controller
{
    /**
     * Rounds that count toward "full participation" = any round where at least one
     * result exists in this tournament. Users missing any of these are ranked after
     * everyone who played them all (fixes sum-of-positions favoring partial play).
     */
    private function activeRoundIdsForTournament($rawResults): \Illuminate\Support\Collection
    {
        return $rawResults->pluck('round_id')->unique()->sort()->values();
    }

    private function userHasPlayedAllActiveRounds($userResultGroup, $activeRoundIds): bool
    {
        if ($activeRoundIds->isEmpty()) {
            return true;
        }

        $played = $userResultGroup->pluck('round_id')->unique();

        return $activeRoundIds->diff($played)->isEmpty();
    }

    public function results($id)
    {
        $tournament = Tournament::findOrFail($id);

        // Fetch all results for this tournament with relations
        $rawResults = Result::where('tournament_id', $id)
            ->with(['user', 'game', 'round'])
            ->get();

        if ($rawResults->isEmpty()) {
            $role = Auth::user()->role;

            $viewData = [
                'heading'    => 'Tournament Results',
                'title'      => 'Results',
                'active'     => 'tournament',
                'tournament' => $tournament,
                'results'    => collect(),
            ];

            return $role == 0
                ? view('admin.tournament.results', $viewData)
                : view('user.tournament.results', $viewData);
        }

        DB::transaction(function () use ($rawResults) {
            /**
             * 1) ASSIGN POSITIONS FOR EACH ROUND + GAME
             *    - Higher score first
             *    - If score equal -> lower time_taken first
             *    - If both equal -> same position
             *    - Next position is skipped (1,2,2,4 style)
             */
            $groupedByRoundGame = $rawResults->groupBy(function ($item) {
                return $item->round_id . '-' . $item->game_id;
            });

            foreach ($groupedByRoundGame as $group) {
                // Sort per round+game
                $sorted = $group->sort(function ($a, $b) {
                    // Score desc
                    if ($a->score !== $b->score) {
                        return $b->score <=> $a->score;
                    }
                    // Time asc
                    return $a->time_taken <=> $b->time_taken;
                })->values();

                $prevScore    = null;
                $prevTime     = null;
                $prevPosition = null;

                foreach ($sorted as $index => $result) {
                    if (
                        $prevScore !== null &&
                        $result->score == $prevScore &&
                        $result->time_taken == $prevTime
                    ) {
                        // Same score + same time → same position
                        $position = $prevPosition;
                    } else {
                        // New distinct rank → index + 1
                        $position = $index + 1;
                    }

                    $result->position = $position;
                    $result->save();

                    $prevScore    = $result->score;
                    $prevTime     = $result->time_taken;
                    $prevPosition = $position;
                }
            }
        });

        // At this point $rawResults objects already have updated position in memory.
        // (They are the same instances we saved.)

        $eliminationType = $tournament->elimination_type;
        $resultsForView  = collect();

        /**
         * 2) LOGIC FOR elimination_type = 'all'
         *    - Sum positions across all rounds (and games) for each user
         *    - Lower total_position = better overall rank
         */
        if ($eliminationType === 'all') {
            $groupedByUser = $rawResults->groupBy('user_id');
            $activeRoundIds = $this->activeRoundIdsForTournament($rawResults);

            $overall = $groupedByUser->map(function ($group) use ($activeRoundIds) {
                $user       = $group->first()->user;
                $totalPos   = $group->sum('position');
                $totalScore = $group->sum('score');
                $totalTime  = $group->sum('time_taken');
                $hasCompletedAllActiveRounds = $this->userHasPlayedAllActiveRounds($group, $activeRoundIds);

                $formattedTotalTime = sprintf(
                    '%02d:%02d',
                    floor($totalTime / 60),
                    $totalTime % 60
                );

                // Per-round details
                $rounds = $group->map(function ($item) {
                    $roundTime = sprintf(
                        '%02d:%02d',
                        floor($item->time_taken / 60),
                        $item->time_taken % 60
                    );

                    return [
                        'game'     => $item->game->title ?? null,
                        'round'    => $item->round->sequence ?? $item->round_id,
                        'score'    => $item->score,
                        'time'     => $roundTime,
                        'position' => $item->position,
                    ];
                })->sortBy('round')->values();

                return [
                    'user'                          => $user,
                    'total_position'                => $totalPos,
                    'total_score'                   => $totalScore,
                    'total_time'                    => $totalTime,
                    'formatted_time'                => $formattedTotalTime,
                    'rounds'                        => $rounds,
                    'has_completed_all_active_rounds' => $hasCompletedAllActiveRounds,
                ];
            });

            // Sort: full participation first, then lower total_position, score, time
            $overall = $overall->sort(function ($a, $b) {
                if ($a['has_completed_all_active_rounds'] !== $b['has_completed_all_active_rounds']) {
                    return (int) $b['has_completed_all_active_rounds']
                        <=> (int) $a['has_completed_all_active_rounds'];
                }

                if ($a['total_position'] !== $b['total_position']) {
                    return $a['total_position'] <=> $b['total_position']; // lower is better
                }

                if ($a['total_score'] !== $b['total_score']) {
                    return $b['total_score'] <=> $a['total_score']; // higher is better
                }

                return $a['total_time'] <=> $b['total_time']; // lower is better
            })->values();

            // Assign final overall rank with tie handling (same tier + total_position → same final rank, skip next)
            $overallArray = $overall->all();
            $prevComposite = null;
            $prevRank     = null;

            foreach ($overallArray as $index => &$row) {
                $composite = sprintf(
                    '%d-%d',
                    (int) $row['has_completed_all_active_rounds'],
                    (int) $row['total_position']
                );

                if ($prevComposite !== null && $composite === $prevComposite) {
                    $row['final_rank'] = $prevRank;
                } else {
                    $row['final_rank'] = $index + 1;
                    $prevRank         = $row['final_rank'];
                    $prevComposite    = $composite;
                }
            }
            unset($row);

            $resultsForView = collect($overallArray);
        }

        /**
         * 3) LOGIC FOR elimination_type = 'percentage'
         *    - Positions are still stored per round (Option B)
         *    - Final positions (1st, 2nd, 3rd) are based on LAST round
         *      that has >= 3 unique users.
         *    - If none has >= 3, use the last round available.
         */
        if ($eliminationType === 'percentage') {
            // Group by round
            $groupedByRound = $rawResults->groupBy('round_id');
            $groupedByUser = $rawResults->groupBy('user_id');
            $activeRoundIds = $this->activeRoundIdsForTournament($rawResults);

            // Sort rounds by round->sequence (if exists) otherwise by round_id
            $sortedRounds = $groupedByRound->sortBy(function ($group, $roundId) {
                $firstRound = $group->first()->round;
                return $firstRound->sequence ?? $roundId;
            });

            // Find last round that has >= 3 unique users
            $selectedRoundId = null;
            foreach ($sortedRounds->reverse() as $roundId => $group) {
                $uniqueUsers = $group->pluck('user_id')->unique()->count();
                if ($uniqueUsers >= 3) {
                    $selectedRoundId = $roundId;
                    break;
                }
            }

            // If no round has >= 3 users, just take the last round
            if (!$selectedRoundId) {
                $selectedRoundId = $sortedRounds->keys()->last();
            }

            $finalRoundResults = $groupedByRound[$selectedRoundId];

            // Aggregate by user in that round
            $finalPerUser = $finalRoundResults->groupBy('user_id')->map(function ($group) use ($groupedByUser, $activeRoundIds) {
                $first    = $group->first();
                $user     = $first->user;
                $score    = $group->sum('score');       // in case multiple results per user in that round
                $time     = $group->sum('time_taken');  // same
                $position = $group->min('position');    // all should have same position per user/round

                $userAll = $groupedByUser->get($user->id, collect());
                $hasCompleted = $this->userHasPlayedAllActiveRounds($userAll, $activeRoundIds);

                $formattedTime = sprintf(
                    '%02d:%02d',
                    floor($time / 60),
                    $time % 60
                );

                return [
                    'user'                             => $user,
                    'score'                            => $score,
                    'time'                             => $time,
                    'formatted_time'                   => $formattedTime,
                    'position'                         => $position,
                    'round'                            => $first->round->sequence ?? $first->round_id,
                    'has_completed_all_active_rounds' => $hasCompleted,
                ];
            });

            // Full participation first, then within-round position / score / time
            $finalPerUser = $finalPerUser->sort(function ($a, $b) {
                if ($a['has_completed_all_active_rounds'] !== $b['has_completed_all_active_rounds']) {
                    return (int) $b['has_completed_all_active_rounds']
                        <=> (int) $a['has_completed_all_active_rounds'];
                }

                if ($a['position'] !== $b['position']) {
                    return $a['position'] <=> $b['position'];
                }

                if ($a['score'] !== $b['score']) {
                    return $b['score'] <=> $a['score'];
                }

                return $a['time'] <=> $b['time'];
            })->values();

            // Leaderboard rank reflects completion-aware order (not only in-round position)
            $finalPerUser = $finalPerUser->map(function ($row, $index) {
                $row['position'] = $index + 1;

                return $row;
            });

            $resultsForView = $finalPerUser;
        }

        $role = Auth::user()->role;

        $viewData = [
            'heading'    => 'Tournament Results',
            'title'      => 'Results',
            'active'     => 'tournament',
            'tournament' => $tournament,
            'results'    => $resultsForView,
        ];

        if ($role == 0) {
            return view('admin.tournament.results', $viewData);
        }

        return view('user.tournament.results', $viewData);
    }

    public function resultsDetails($id)
    {
        $tournament = Tournament::findOrFail($id);

        // Fetch all results for this tournament with relations
        $rawResults = Result::where('tournament_id', $id)
            ->with(['user', 'game', 'round'])
            ->get();

        if ($rawResults->isEmpty()) {
            return view('user.tournament.results-details', [
                'tournament' => $tournament,
                'players' => collect(),
            ]);
        }

        // Ensure positions are calculated (same logic as results method)
        DB::transaction(function () use ($rawResults) {
            $groupedByRoundGame = $rawResults->groupBy(function ($item) {
                return $item->round_id . '-' . $item->game_id;
            });

            foreach ($groupedByRoundGame as $group) {
                $sorted = $group->sort(function ($a, $b) {
                    if ($a->score !== $b->score) {
                        return $b->score <=> $a->score;
                    }
                    return $a->time_taken <=> $b->time_taken;
                })->values();

                $prevScore = null;
                $prevTime = null;
                $prevPosition = null;

                foreach ($sorted as $index => $result) {
                    if (
                        $prevScore !== null &&
                        $result->score == $prevScore &&
                        $result->time_taken == $prevTime
                    ) {
                        $position = $prevPosition;
                    } else {
                        $position = $index + 1;
                    }

                    $result->position = $position;
                    $result->save();

                    $prevScore = $result->score;
                    $prevTime = $result->time_taken;
                    $prevPosition = $position;
                }
            }
        });

        // Group by user and prepare data for each player
        $groupedByUser = $rawResults->groupBy('user_id');

        // Calculate overall positions for each user
        $eliminationType = $tournament->elimination_type;
        $playersData = collect();

        if ($eliminationType === 'all') {
            $activeRoundIds = $this->activeRoundIdsForTournament($rawResults);

            // For 'all' type: calculate overall rank based on sum of positions
            $overall = $groupedByUser->map(function ($group) use ($activeRoundIds) {
                $user = $group->first()->user;
                $totalPos = $group->sum('position');
                $totalScore = $group->sum('score');
                $totalTime = $group->sum('time_taken');
                $hasCompleted = $this->userHasPlayedAllActiveRounds($group, $activeRoundIds);

                $rounds = $group->map(function ($item) {
                    $minutes = floor($item->time_taken / 60);
                    $seconds = $item->time_taken % 60;
                    $roundTime = sprintf('%02d:%02d', $minutes, $seconds);

                    return [
                        'round_number' => $item->round->sequence ?? $item->round_id,
                        'score' => $item->score,
                        'time_taken' => $item->time_taken,
                        'time_formatted' => $roundTime,
                        'position' => $item->position,
                    ];
                })->sortBy('round_number')->values();

                return [
                    'user' => $user,
                    'total_position' => $totalPos,
                    'total_score' => $totalScore,
                    'total_time' => $totalTime,
                    'rounds' => $rounds,
                    'has_completed_all_active_rounds' => $hasCompleted,
                ];
            });

            // Sort and assign ranks
            $overall = $overall->sort(function ($a, $b) {
                if ($a['has_completed_all_active_rounds'] !== $b['has_completed_all_active_rounds']) {
                    return (int) $b['has_completed_all_active_rounds']
                        <=> (int) $a['has_completed_all_active_rounds'];
                }
                if ($a['total_position'] !== $b['total_position']) {
                    return $a['total_position'] <=> $b['total_position'];
                }
                if ($a['total_score'] !== $b['total_score']) {
                    return $b['total_score'] <=> $a['total_score'];
                }
                return $a['total_time'] <=> $b['total_time'];
            })->values();

            $overallArray = $overall->all();
            $prevComposite = null;
            $prevRank = null;

            foreach ($overallArray as $index => &$row) {
                $composite = sprintf(
                    '%d-%d',
                    (int) $row['has_completed_all_active_rounds'],
                    (int) $row['total_position']
                );

                if ($prevComposite !== null && $composite === $prevComposite) {
                    $row['overall_position'] = $prevRank;
                } else {
                    $row['overall_position'] = $index + 1;
                    $prevRank = $row['overall_position'];
                    $prevComposite = $composite;
                }
            }
            unset($row);

            $playersData = collect($overallArray);
        } else {
            // For 'percentage' type: use position from final round
            $activeRoundIds = $this->activeRoundIdsForTournament($rawResults);
            $groupedByRound = $rawResults->groupBy('round_id');
            $sortedRounds = $groupedByRound->sortBy(function ($group, $roundId) {
                $firstRound = $group->first()->round;
                return $firstRound->sequence ?? $roundId;
            });

            $selectedRoundId = null;
            foreach ($sortedRounds->reverse() as $roundId => $group) {
                $uniqueUsers = $group->pluck('user_id')->unique()->count();
                if ($uniqueUsers >= 3) {
                    $selectedRoundId = $roundId;
                    break;
                }
            }

            if (!$selectedRoundId) {
                $selectedRoundId = $sortedRounds->keys()->last();
            }

            $finalRoundResults = $groupedByRound[$selectedRoundId];
            $finalPerUser = $finalRoundResults->groupBy('user_id')->map(function ($group) use ($groupedByUser, $activeRoundIds) {
                $first = $group->first();
                $score = $group->sum('score');
                $time = $group->sum('time_taken');
                $position = $group->min('position');
                $userAll = $groupedByUser->get($first->user_id, collect());
                $hasCompleted = $this->userHasPlayedAllActiveRounds($userAll, $activeRoundIds);

                return [
                    'user_id' => $first->user_id,
                    'position' => $position,
                    'score' => $score,
                    'time' => $time,
                    'has_completed_all_active_rounds' => $hasCompleted,
                ];
            });

            // Full participation first, then in-round position / score / time
            $sortedFinalUsers = $finalPerUser->sort(function ($a, $b) {
                if ($a['has_completed_all_active_rounds'] !== $b['has_completed_all_active_rounds']) {
                    return (int) $b['has_completed_all_active_rounds']
                        <=> (int) $a['has_completed_all_active_rounds'];
                }
                if ($a['position'] !== $b['position']) {
                    return $a['position'] <=> $b['position'];
                }
                if ($a['score'] !== $b['score']) {
                    return $b['score'] <=> $a['score'];
                }
                return $a['time'] <=> $b['time'];
            })->values();

            // Assign final ranks with tie handling (same completion tier + in-round position → same rank)
            $rankedUsers = [];
            $prevTieKey = null;
            $prevRank = null;

            foreach ($sortedFinalUsers as $index => $userData) {
                $tieKey = sprintf(
                    '%d-%d',
                    (int) $userData['has_completed_all_active_rounds'],
                    (int) $userData['position']
                );

                if ($prevTieKey !== null && $tieKey === $prevTieKey) {
                    $rankedUsers[$userData['user_id']] = $prevRank;
                } else {
                    $rank = $index + 1;
                    $rankedUsers[$userData['user_id']] = $rank;
                    $prevRank = $rank;
                    $prevTieKey = $tieKey;
                }
            }

            // Get the highest rank from final round users
            $maxFinalRank = count($rankedUsers);

            // Build players data with all rounds - include ALL users
            $playersData = $groupedByUser->map(function ($group) use ($rankedUsers, $maxFinalRank) {
                $user = $group->first()->user;

                // If user is in final round, use their calculated rank
                if (isset($rankedUsers[$user->id])) {
                    $overallPosition = $rankedUsers[$user->id];
                } else {
                    // User was eliminated - assign position after final round participants
                    // We'll sort these by their last round performance later
                    $overallPosition = $maxFinalRank + 1000; // High number to put them after final round users
                }

                $rounds = $group->map(function ($item) {
                    $minutes = floor($item->time_taken / 60);
                    $seconds = $item->time_taken % 60;
                    $roundTime = sprintf('%02d:%02d', $minutes, $seconds);

                    return [
                        'round_number' => $item->round->sequence ?? $item->round_id,
                        'score' => $item->score,
                        'time_taken' => $item->time_taken,
                        'time_formatted' => $roundTime,
                        'position' => $item->position,
                    ];
                })->sortBy('round_number')->values();

                return [
                    'user' => $user,
                    'overall_position' => $overallPosition,
                    'rounds' => $rounds,
                ];
            });

            // Sort by overall position
            $playersData = $playersData->sortBy('overall_position')->values();

            // Re-assign sequential positions (1, 2, 3, 4, etc.) to all players
            $finalPlayersData = collect();
            $currentRank = 1;
            $prevPosition = null;

            foreach ($playersData as $player) {
                // If this player has the same position value as previous, keep same rank
                if ($prevPosition !== null && $player['overall_position'] == $prevPosition) {
                    $player['overall_position'] = $currentRank;
                } else {
                    $player['overall_position'] = $currentRank;
                    $currentRank++;
                }
                $prevPosition = $player['overall_position'];
                $finalPlayersData->push($player);
            }

            $playersData = $finalPlayersData;
        }

        return view('user.tournament.results-details', [
            'tournament' => $tournament,
            'players' => $playersData,
        ]);
    }
}
