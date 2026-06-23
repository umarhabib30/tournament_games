<?php

namespace App\Http\Controllers;

use App\Events\PermissionRequestEvent;
use App\Models\Game;
use App\Models\Result;
use App\Models\Round;
use App\Models\Tournament;
use App\Models\TournamentPermission;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserTournamentController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();
        $yesterday = now()->subDay()->toDateString();

        $data = [
            'tournaments' => Tournament::whereDate('date', '>=', $today)
                ->orWhereDate('date', $yesterday)
                ->orderBy('date', 'desc')
                ->get(),
        ];

        return view('user.tournament.index', $data);
    }

    public function waiting($id)
    {
        $tournament = Tournament::findOrFail($id);

        $now = Carbon::now('Asia/Karachi')->copy()->seconds(0)->milliseconds(0);

        // ✅ Merge date + time dynamically before parsing
        $startTime = Carbon::parse($tournament->date . ' ' . $tournament->start_time, 'Asia/Karachi')->copy()->seconds(0)->milliseconds(0);
        $endTime = Carbon::parse($tournament->date . ' ' . $tournament->end_time, 'Asia/Karachi')->copy()->seconds(0)->milliseconds(0);

        // Debug
        // dd(compact('now', 'entryTime', 'startTime', 'endTime'));

        // 1️⃣ Tournament ended?
        if ($now >= $endTime) {
            if ($tournament->results_published) {
                return redirect()->route('tournament.results', $tournament->id);
            }

            return redirect()
                ->route('tournament')
                ->with('info', 'Tournament has ended. Waiting for admin to open results.');
        }

        $permissionRedirect = $this->closedTournamentPermissionRedirect($tournament);
        if ($permissionRedirect) {
            return $permissionRedirect;
        }

        $result = Result::where('tournament_id', $tournament->id)
            ->where('user_id', Auth::user()->id)
            ->first();
        if ($result) {
            $data = [
                'tournament' => Tournament::find($id),
            ];

            return view('user.tournament.games', $data);
        }

        // 2️⃣ Tournament already started?
        // Timed tournaments: block late entry
        // Free-form tournaments: allow entry even after start (until endTime, already checked above)
        if ($now >= $startTime) {
            if ($tournament->time_or_free === 'time') {
                return redirect()->back()->with('error', 'Tournament has already started. You cannot join now.');
            }

            // Free form: jump directly into tournament
            return redirect()->route('play', $tournament->id);
        }

        if ($tournament->time_to_enter) {
            $entryTime = Carbon::parse($tournament->date . ' ' . $tournament->time_to_enter, 'Asia/Karachi')->copy()->seconds(0)->milliseconds(0);

            // // 3️⃣ Entry time passed?
            if ($now >= $entryTime) {
                return redirect()->back()->with('error', 'Tournament entry time has passed.');
            }
        }

        return view('user.tournament.waiting', [
            'tournament' => $tournament,
        ]);
    }

    public function play($id)
    {
        $tournament = Tournament::findOrFail($id);

        $permissionRedirect = $this->closedTournamentPermissionRedirect($tournament);
        if ($permissionRedirect) {
            return $permissionRedirect;
        }

        $data = [
            'tournament' => $tournament,
        ];

        return view('user.tournament.games', $data);
    }

    public function playGame(Request $request)
    {
        $tournament = Tournament::findOrFail($request->tournament_id);
        $permissionRedirect = $this->closedTournamentPermissionRedirect($tournament);
        if ($permissionRedirect) {
            return $permissionRedirect;
        }

        $round = Round::with(['gameLevel.game', 'get_game'])->find($request->round_id);

        // Resolve game + view slug from configured level (preferred) or fallback game
        $gameLevel = $round?->gameLevel;
        $game = $gameLevel?->game ?? Game::find($request->game_id);
        $viewSlug = $gameLevel?->level_slug ?? $game?->slug;

        $userId = Auth::id();

        // ✅ Apply elimination check if applicable
        if ($round->sequence > 1 && $tournament->elimination_type === 'percentage') {
            $previousRound = Round::where('tournament_id', $tournament->id)
                ->where('sequence', $round->sequence - 1)
                ->first();

            if ($previousRound) {
                // ✅ Fetch all results for previous round
                $results = Result::where('round_id', $previousRound->id)
                    ->select('user_id', DB::raw('SUM(score) as total_score'), DB::raw('SUM(time_taken) as total_time'))
                    ->groupBy('user_id')
                    ->get()
                    // ✅ Sort by total_score DESC, total_time ASC (same logic as results)
                    ->sort(function ($a, $b) {
                        if ($a->total_score !== $b->total_score) {
                            return $b->total_score <=> $a->total_score;  // higher score first
                        }
                        return $a->total_time <=> $b->total_time;  // less time first
                    })
                    ->values();

                $count = $results->count();

                // ✅ Calculate survivors based on elimination percentage
                $allowed = ceil($count * ((100 - $tournament->elimination_percent) / 100));

                // ✅ Get IDs of top survivors
                $qualifiedUserIds = $results->take($allowed)->pluck('user_id')->toArray();

                // ✅ Elimination check
                if (!in_array($userId, $qualifiedUserIds)) {
                    return redirect()->back()->with('error', 'You have been eliminated from this tournament.');
                }
            }
        }

        // ✅ Server time for accurate synchronization
        $serverNow = now()->timestamp;

        // ✅ Determine end time depending on "time_or_free" flag
        if ($tournament->time_or_free === 'time') {
            $endTime = $this->convertTimeToTimestamp($round->end_time);
        } else {
            $endTime = $this->convertTimeToTimestamp($tournament->end_time);
        }

        $now = Carbon::now('Asia/Karachi')->copy()->seconds(0)->milliseconds(0);
        $startTime = Carbon::parse($tournament->date . ' ' . $round->start_time, 'Asia/Karachi')->copy()->seconds(0)->milliseconds(0);

        // Timed tournaments: enforce round start time
        // Free form: allow play without waiting for round start time
        if ($tournament->time_or_free === 'time' && $now < $startTime) {
            return redirect()->back()->with('error', 'Round has not started yet.');
        }

        $data = [
            'game' => $game,
            'tournament' => $tournament,
            'round' => $round,
            'gameStartTime' => $serverNow,
            'serverNow' => $serverNow,
            'endtime' => $endTime,
        ];


        return view('user.games.' . $viewSlug, $data + ['gameLevel' => $gameLevel]);
    }

    public function submitScore(Request $request)
    {
        $data = $request->validate([
            'tournament_id' => 'required|exists:tournaments,id',
            'round_id' => 'required|exists:rounds,id',
            'game_id' => 'required|exists:games,id',
            'score' => 'required|numeric',
            'time_taken' => 'required|numeric'
        ]);

        $tournament = Tournament::findOrFail($request->tournament_id);
        $permissionError = $this->closedTournamentPermissionError($tournament);
        if ($permissionError) {
            return response()->json([
                'success' => false,
                'message' => $permissionError,
            ], 403);
        }

        $userId = auth()->id();

        // ✅ Check for existing result (duplicate prevention)
        $existing = Result::where('tournament_id', $request->tournament_id)
            ->where('round_id', $request->round_id)
            ->where('game_id', $request->game_id)
            ->where('user_id', $userId)
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'You have already submitted your score for this game.'
            ], 409);
        }

        // ✅ Create new result if no duplicate found
        Result::create([
            'tournament_id' => $request->tournament_id,
            'round_id' => $request->round_id,
            'game_id' => $request->game_id,
            'user_id' => $userId,
            'score' => $request->score,
            'time_taken' => $request->time_taken,
            'status' => 'completed',
        ]);

        return response()->json(['success' => true]);
    }

    // helper functions

    private function convertTimeToTimestamp($timeString)
    {
        // If it's already a timestamp, just return it
        if (is_numeric($timeString)) {
            return (int) $timeString;
        }

        // If only time given (like '12:48:00'), assume today's date + LOCAL TIMEZONE
        if (preg_match('/^\d{2}:\d{2}:\d{2}$/', $timeString)) {
            return \Carbon\Carbon::today('Asia/Karachi')  // ✅ Force timezone
                ->setTimeFromTimeString($timeString)
                ->timestamp;
        }

        // Otherwise parse full datetime in LOCAL TIMEZONE
        return \Carbon\Carbon::parse($timeString, 'Asia/Karachi')->timestamp;
    }

    private function closedTournamentPermissionRedirect(Tournament $tournament)
    {
        $permissionError = $this->closedTournamentPermissionError($tournament);

        if (!$permissionError) {
            return null;
        }

        return redirect('request/permission/' . $tournament->id)->with('error', $permissionError);
    }

    private function closedTournamentPermissionError(Tournament $tournament)
    {
        if ($tournament->open_close !== 'close') {
            return null;
        }

        $permission = TournamentPermission::where('user_id', Auth::id())
            ->where('tournament_id', $tournament->id)
            ->first();

        if (!$permission) {
            return 'Please submit a request to admin to enter the tournament';
        }

        if ($permission->status === 'Pending') {
            return 'Your request is Pending';
        }

        if ($permission->status === 'Rejected') {
            return 'Your request is Rejected';
        }

        if ($permission->status !== 'Accepted') {
            return 'Your request is not approved yet';
        }

        return null;
    }

    // request permission for the tournament
    public function permissionPage($id)
    {
        $tournament = Tournament::findOrFail($id);
        $permission = TournamentPermission::where('user_id', Auth::id())
            ->where('tournament_id', $id)
            ->first();

        $uiState = match (true) {
            !$permission => 'submit',
            $permission->status === 'Pending' => 'pending',
            $permission->status === 'Accepted' => 'accepted',
            $permission->status === 'Rejected' => 'rejected',
            default => 'submit',
        };

        $data = [
            'tournament' => $tournament,
            'status' => $permission->status ?? null,
            'uiState' => $uiState,
        ];

        return view('user.tournament.permission', $data);
    }

    public function permissionStatus($id)
    {
        $permission = TournamentPermission::where('user_id', Auth::id())
            ->where('tournament_id', $id)
            ->first();

        return response()->json([
            'status' => $permission->status ?? 'None',
        ]);
    }

    public function requestPermission($id)
    {
        $tournament = Tournament::findOrFail($id);

        $existing = TournamentPermission::where('user_id', Auth::id())
            ->where('tournament_id', $id)
            ->first();

        if ($existing) {
            return redirect()
                ->route('request.permission.page', $id)
                ->with('error', 'You have already submitted a request for this tournament.');
        }

        event(new PermissionRequestEvent($tournament->name, Auth::user()->username));
        TournamentPermission::create([
            'user_id' => Auth::id(),
            'tournament_id' => $id,
        ]);

        return redirect()
            ->route('request.permission.page', $id)
            ->with('success', 'Your request has been submitted. Waiting for admin approval.');
    }
}
