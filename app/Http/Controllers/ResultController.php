<?php

namespace App\Http\Controllers;

use App\Models\Result;
use App\Models\Round;
use App\Models\Tournament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ResultController extends Controller
{
    /**
     * All rounds configured for the tournament (preferred).
     * Falls back to rounds that have at least one result if none are configured.
     */
    private function expectedRoundIdsForTournament(Tournament $tournament, $rawResults): \Illuminate\Support\Collection
    {
        $configured = Round::where('tournament_id', $tournament->id)
            ->orderBy('sequence')
            ->pluck('id')
            ->values();

        if ($configured->isNotEmpty()) {
            return $configured;
        }

        return $rawResults->pluck('round_id')->unique()->sort()->values();
    }

    private function roundsPlayedCount($userResultGroup): int
    {
        return $userResultGroup->pluck('round_id')->unique()->count();
    }

    private function userHasPlayedAllActiveRounds($userResultGroup, $expectedRoundIds): bool
    {
        if ($expectedRoundIds->isEmpty()) {
            return true;
        }

        $played = $userResultGroup->pluck('round_id')->unique();

        return $expectedRoundIds->diff($played)->isEmpty();
    }

    /**
     * Leaderboard order: more rounds played first, then lower sum of round positions,
     * then higher score, then lower time.
     */
    private function compareLeaderboardRows(array $a, array $b): int
    {
        $roundsA = $a['rounds_played'] ?? 0;
        $roundsB = $b['rounds_played'] ?? 0;

        if ($roundsA !== $roundsB) {
            return $roundsB <=> $roundsA;
        }

        $posA = $a['total_position'] ?? $a['position'] ?? PHP_INT_MAX;
        $posB = $b['total_position'] ?? $b['position'] ?? PHP_INT_MAX;

        if ($posA !== $posB) {
            return $posA <=> $posB;
        }

        $scoreA = $a['total_score'] ?? $a['score'] ?? 0;
        $scoreB = $b['total_score'] ?? $b['score'] ?? 0;

        if ($scoreA !== $scoreB) {
            return $scoreB <=> $scoreA;
        }

        $timeA = $a['total_time'] ?? $a['time'] ?? PHP_INT_MAX;
        $timeB = $b['total_time'] ?? $b['time'] ?? PHP_INT_MAX;

        return $timeA <=> $timeB;
    }

    private function assignSequentialRanks(array $rows, callable $tieKey): array
    {
        $prevKey = null;
        $prevRank = null;

        foreach ($rows as $index => &$row) {
            $key = $tieKey($row);

            if ($prevKey !== null && $key === $prevKey) {
                $row['final_rank'] = $prevRank;
                $row['overall_position'] = $prevRank;
                $row['position'] = $prevRank;
            } else {
                $rank = $index + 1;
                $row['final_rank'] = $rank;
                $row['overall_position'] = $rank;
                if (array_key_exists('position', $row)) {
                    $row['position'] = $rank;
                }
                $prevRank = $rank;
                $prevKey = $key;
            }
        }
        unset($row);

        return $rows;
    }

    private function leaderboardTieKey(array $row): string
    {
        return sprintf(
            '%d-%d',
            (int) ($row['rounds_played'] ?? 0),
            (int) ($row['total_position'] ?? $row['position'] ?? 0)
        );
    }

    private function ensureResultsPublishedForUser(Tournament $tournament)
    {
        if (Auth::user()->role == 0) {
            return null;
        }

        if (!$tournament->results_published) {
            return redirect()
                ->route('tournament')
                ->with('error', 'Results are not available yet. Waiting for admin to open results.');
        }

        return null;
    }

    public function results($id)
    {
        $tournament = Tournament::findOrFail($id);

        $accessRedirect = $this->ensureResultsPublishedForUser($tournament);
        if ($accessRedirect) {
            return $accessRedirect;
        }

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
            $expectedRoundIds = $this->expectedRoundIdsForTournament($tournament, $rawResults);

            $overall = $groupedByUser->map(function ($group) use ($expectedRoundIds) {
                $user       = $group->first()->user;
                $totalPos   = $group->sum('position');
                $totalScore = $group->sum('score');
                $totalTime  = $group->sum('time_taken');
                $roundsPlayed = $this->roundsPlayedCount($group);
                $hasCompletedAllActiveRounds = $this->userHasPlayedAllActiveRounds($group, $expectedRoundIds);

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
                    'user'                            => $user,
                    'rounds_played'                   => $roundsPlayed,
                    'total_position'                  => $totalPos,
                    'total_score'                     => $totalScore,
                    'total_time'                      => $totalTime,
                    'formatted_time'                  => $formattedTotalTime,
                    'rounds'                          => $rounds,
                    'has_completed_all_active_rounds' => $hasCompletedAllActiveRounds,
                ];
            });

            $overall = $overall->sort(function ($a, $b) {
                return $this->compareLeaderboardRows($a, $b);
            })->values();

            $overallArray = $this->assignSequentialRanks($overall->all(), function ($row) {
                return $this->leaderboardTieKey($row);
            });

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
            $groupedByUser = $rawResults->groupBy('user_id');
            $expectedRoundIds = $this->expectedRoundIdsForTournament($tournament, $rawResults);

            $finalPerUser = $groupedByUser->map(function ($group) use ($expectedRoundIds) {
                $user = $group->first()->user;
                $totalPos = $group->sum('position');
                $totalScore = $group->sum('score');
                $totalTime = $group->sum('time_taken');
                $roundsPlayed = $this->roundsPlayedCount($group);
                $hasCompleted = $this->userHasPlayedAllActiveRounds($group, $expectedRoundIds);

                $lastResult = $group->sortBy(function ($item) {
                    return $item->round->sequence ?? $item->round_id;
                })->last();

                $formattedTime = sprintf(
                    '%02d:%02d',
                    floor($totalTime / 60),
                    $totalTime % 60
                );

                return [
                    'user'                            => $user,
                    'rounds_played'                   => $roundsPlayed,
                    'total_position'                  => $totalPos,
                    'score'                           => $totalScore,
                    'total_score'                     => $totalScore,
                    'time'                            => $totalTime,
                    'total_time'                      => $totalTime,
                    'formatted_time'                  => $formattedTime,
                    'position'                        => $lastResult->position,
                    'round'                           => $lastResult->round->sequence ?? $lastResult->round_id,
                    'has_completed_all_active_rounds' => $hasCompleted,
                ];
            });

            $finalPerUser = $finalPerUser->sort(function ($a, $b) {
                return $this->compareLeaderboardRows($a, $b);
            })->values();

            $ranked = $this->assignSequentialRanks($finalPerUser->all(), function ($row) {
                return $this->leaderboardTieKey($row);
            });

            $resultsForView = collect($ranked);
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

        $accessRedirect = $this->ensureResultsPublishedForUser($tournament);
        if ($accessRedirect) {
            return $accessRedirect;
        }

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
            $expectedRoundIds = $this->expectedRoundIdsForTournament($tournament, $rawResults);

            // For 'all' type: calculate overall rank based on sum of positions
            $overall = $groupedByUser->map(function ($group) use ($expectedRoundIds) {
                $user = $group->first()->user;
                $totalPos = $group->sum('position');
                $totalScore = $group->sum('score');
                $totalTime = $group->sum('time_taken');
                $roundsPlayed = $this->roundsPlayedCount($group);
                $hasCompleted = $this->userHasPlayedAllActiveRounds($group, $expectedRoundIds);

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
                    'rounds_played' => $roundsPlayed,
                    'total_position' => $totalPos,
                    'total_score' => $totalScore,
                    'total_time' => $totalTime,
                    'rounds' => $rounds,
                    'has_completed_all_active_rounds' => $hasCompleted,
                ];
            });

            $overall = $overall->sort(function ($a, $b) {
                return $this->compareLeaderboardRows($a, $b);
            })->values();

            $overallArray = $this->assignSequentialRanks($overall->all(), function ($row) {
                return $this->leaderboardTieKey($row);
            });

            $playersData = collect($overallArray);
        } else {
            $expectedRoundIds = $this->expectedRoundIdsForTournament($tournament, $rawResults);

            $overall = $groupedByUser->map(function ($group) use ($expectedRoundIds) {
                $user = $group->first()->user;
                $totalPos = $group->sum('position');
                $totalScore = $group->sum('score');
                $totalTime = $group->sum('time_taken');
                $roundsPlayed = $this->roundsPlayedCount($group);
                $hasCompleted = $this->userHasPlayedAllActiveRounds($group, $expectedRoundIds);

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
                    'rounds_played' => $roundsPlayed,
                    'total_position' => $totalPos,
                    'total_score' => $totalScore,
                    'total_time' => $totalTime,
                    'rounds' => $rounds,
                    'has_completed_all_active_rounds' => $hasCompleted,
                ];
            });

            $overall = $overall->sort(function ($a, $b) {
                return $this->compareLeaderboardRows($a, $b);
            })->values();

            $playersData = collect($this->assignSequentialRanks($overall->all(), function ($row) {
                return $this->leaderboardTieKey($row);
            }));
        }

        return view('user.tournament.results-details', [
            'tournament' => $tournament,
            'players' => $playersData,
        ]);
    }
}
