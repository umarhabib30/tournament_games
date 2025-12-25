<?php

namespace App\Http\Controllers;

use App\Models\Result;
use App\Models\Tournament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ResultController extends Controller
{
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

            $overall = $groupedByUser->map(function ($group) {
                $user       = $group->first()->user;
                $totalPos   = $group->sum('position');
                $totalScore = $group->sum('score');
                $totalTime  = $group->sum('time_taken');

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
                    'user'           => $user,
                    'total_position' => $totalPos,
                    'total_score'    => $totalScore,
                    'total_time'     => $totalTime,
                    'formatted_time' => $formattedTotalTime,
                    'rounds'         => $rounds,
                ];
            });

            // Sort: lower total_position first, then higher total_score, then lower total_time
            $overall = $overall->sort(function ($a, $b) {
                if ($a['total_position'] !== $b['total_position']) {
                    return $a['total_position'] <=> $b['total_position']; // lower is better
                }

                if ($a['total_score'] !== $b['total_score']) {
                    return $b['total_score'] <=> $a['total_score']; // higher is better
                }

                return $a['total_time'] <=> $b['total_time']; // lower is better
            })->values();

            // Assign final overall rank with tie handling (same total_position → same final rank, skip next)
            $overallArray = $overall->all();
            $prevTotalPos = null;
            $prevRank     = null;

            foreach ($overallArray as $index => &$row) {
                if ($prevTotalPos !== null && $row['total_position'] == $prevTotalPos) {
                    $row['final_rank'] = $prevRank;
                } else {
                    $row['final_rank'] = $index + 1;
                    $prevRank          = $row['final_rank'];
                    $prevTotalPos      = $row['total_position'];
                }
            }

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
            $finalPerUser = $finalRoundResults->groupBy('user_id')->map(function ($group) {
                $first    = $group->first();
                $user     = $first->user;
                $score    = $group->sum('score');       // in case multiple results per user in that round
                $time     = $group->sum('time_taken');  // same
                $position = $group->min('position');    // all should have same position per user/round

                $formattedTime = sprintf(
                    '%02d:%02d',
                    floor($time / 60),
                    $time % 60
                );

                return [
                    'user'           => $user,
                    'score'          => $score,
                    'time'           => $time,
                    'formatted_time' => $formattedTime,
                    'position'       => $position,
                    'round'          => $first->round->sequence ?? $first->round_id,
                ];
            });

            // Sort by position asc (1st,2nd,3rd...) already has tie logic from per-round calc
            $finalPerUser = $finalPerUser->sort(function ($a, $b) {
                if ($a['position'] !== $b['position']) {
                    return $a['position'] <=> $b['position'];
                }

                // Optional extra tie-breaker: higher score, then lower time
                if ($a['score'] !== $b['score']) {
                    return $b['score'] <=> $a['score'];
                }

                return $a['time'] <=> $b['time'];
            })->values();

            // You can choose to limit to top 3 here if desired:
            // $finalPerUser = $finalPerUser->take(3);

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
            // For 'all' type: calculate overall rank based on sum of positions
            $overall = $groupedByUser->map(function ($group) {
                $user = $group->first()->user;
                $totalPos = $group->sum('position');
                $totalScore = $group->sum('score');
                $totalTime = $group->sum('time_taken');

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
                ];
            });

            // Sort and assign ranks
            $overall = $overall->sort(function ($a, $b) {
                if ($a['total_position'] !== $b['total_position']) {
                    return $a['total_position'] <=> $b['total_position'];
                }
                if ($a['total_score'] !== $b['total_score']) {
                    return $b['total_score'] <=> $a['total_score'];
                }
                return $a['total_time'] <=> $b['total_time'];
            })->values();

            $overallArray = $overall->all();
            $prevTotalPos = null;
            $prevRank = null;

            foreach ($overallArray as $index => &$row) {
                if ($prevTotalPos !== null && $row['total_position'] == $prevTotalPos) {
                    $row['overall_position'] = $prevRank;
                } else {
                    $row['overall_position'] = $index + 1;
                    $prevRank = $row['overall_position'];
                    $prevTotalPos = $row['total_position'];
                }
            }

            $playersData = collect($overallArray);
        } else {
            // For 'percentage' type: use position from final round
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
            $finalPerUser = $finalRoundResults->groupBy('user_id')->map(function ($group) {
                return [
                    'user_id' => $group->first()->user_id,
                    'position' => $group->min('position'),
                ];
            });

            // Build players data with all rounds
            $playersData = $groupedByUser->map(function ($group) use ($finalPerUser) {
                $user = $group->first()->user;
                $overallPosition = $finalPerUser[$user->id]['position'] ?? 999;

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
        }

        return view('user.tournament.results-details', [
            'tournament' => $tournament,
            'players' => $playersData,
        ]);
    }
}
