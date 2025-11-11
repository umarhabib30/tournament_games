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

class UserTournamentController extends Controller
{
    public function index()
    {
        $data = [
            'tournaments' => Tournament::orderBy('updated_at', 'desc')->get(),
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
            return redirect()->route('tournament.results', $tournament->id);
        }

        // 2️⃣ Tournament already started?
        if ($now >= $startTime) {
            return redirect()->back()->with('error', 'Tournament has already started. You cannot join now.');
        }

        if($tournament->time_to_enter){
        $entryTime = Carbon::parse($tournament->date . ' ' . $tournament->time_to_enter, 'Asia/Karachi')->copy()->seconds(0)->milliseconds(0);

        // // 3️⃣ Entry time passed?
        if ($now >= $entryTime) {
            return redirect()->back()->with('error', 'Tournament entry time has passed.');
        }
        }

        if ($tournament->open_close == 'close') {
            $check_permission = TournamentPermission::where('user_id', Auth::user()->id)->where('tournament_id', $tournament->id)->first();
            if (!$check_permission) {
                return redirect('request/permission/' . $tournament->id)->with('error', 'Plesae submit a request to admin to enter the tournament');
            }
            if ($check_permission->status == 'Pending') {
                return redirect('request/permission/' . $tournament->id)->with('error', 'Your request is Pending');
            }
            if ($check_permission->status == 'Rejected') {
                return redirect('request/permission/' . $tournament->id)->with('error', 'Your request is Rejected');
            }
        }

        return view('user.tournament.waiting', [
            'tournament' => $tournament,
        ]);
    }

    public function play($id)
    {
        $data = [
            'tournament' => Tournament::find($id),
        ];

        return view('user.tournament.games', $data);
    }

    public function playGame(Request $request)
    {
        $game = Game::find($request->game_id);
        $tournament = Tournament::find($request->tournament_id);
        $round = Round::find($request->round_id);

        $userId = Auth::user()->id;
        if ($round->sequence > 1 && $tournament->elimination_type === 'percentage') {
            $previousRound = Round::where('tournament_id', $tournament->id)
                ->where('sequence', $round->sequence - 1)
                ->first();

            // Get all results for previous round
            $results = Result::where('round_id', $previousRound->id)
                ->orderByDesc('score')
                ->get();

            $count = $results->count();

            // Calculate allowed survivors based on percentage
            $allowed = ceil($count * ((100 - $tournament->elimination_percent) / 100));

            // Take the top survivors
            $qualifiedUserIds = $results->take($allowed)->pluck('user_id')->toArray();

            // If current user NOT in survivors list → block access
            if (!in_array($userId, $qualifiedUserIds)) {
                return redirect()->back()->with('error', 'You have been eliminated from this tournament.');
            }
        }

        $serverNow = now()->timestamp;  // ✅ pass server time for clock skew correction

        if ($tournament->time_or_free === 'time') {
            $endTime = $this->convertTimeToTimestamp($round->end_time);
        } else {
            $endTime = $this->convertTimeToTimestamp($tournament->end_time);
        }
        $now = Carbon::now('Asia/Karachi')->copy()->seconds(0)->milliseconds(0);

        // ✅ Merge date + time dynamically before parsing
        $startTime = Carbon::parse($tournament->date . ' ' . $round->start_time, 'Asia/Karachi')->copy()->seconds(0)->milliseconds(0);

         if ($now < $startTime) {
            return redirect()->back()->with('error', 'Round is not started yet');
        }

        $data = [
            'game' => $game,
            'tournament' => $tournament,
            'round' => $round,
            'gameStartTime' => $serverNow,
            'serverNow' => $serverNow,
            'endtime' => $endTime,  // ✅ Now properly converted
        ];

        return view('user.games.' . $game->slug, $data);
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
        Result::create([
            'tournament_id' => $request->tournament_id,
            'round_id' => $request->round_id,
            'game_id' => $request->game_id,
            'user_id' => auth()->id(),
            'score' => $request->score,
            'time_taken' => $request->time_taken,
            'status' => 'completed',
        ]);

        return response()->json(['success' => true]);
    }

    public function results($id)
    {
        $tournament = Tournament::find($id);

        // Fetch all results for this tournament with needed relations
        $rawResults = Result::where('tournament_id', $id)
            ->with(['user', 'game', 'round'])
            ->get();

        // Group results by user and game
        $structuredResults = $rawResults->groupBy(function ($item) {
            return $item->user_id . '-' . $item->game_id;
        })->map(function ($group) {
            $first = $group->first();

            return [
                'user' => $first->user,  // Full user model (or use ->only([...]) if needed)
                'rounds' => $group->map(function ($item) {
                    return [
                        'game' => $item->game->title,
                        'round' => $item->round->sequence ?? null,
                        'result' => $item->score ?? $item->status ?? null,  // adapt to your field
                        'time' => $item->time_taken ?? null,
                    ];
                })->values()
            ];
        })->values();  // reset indexes

        // After structuring results

        $structuredResults = $structuredResults->sortByDesc(function ($item) {
            // Assuming score is inside the 'rounds' array; take the sum or highest
            return $item['rounds']->sum('result');  // or ->max('result')
        })->values()->map(function ($item, $index) {
            $item['position'] = $index + 1;
            return $item;
        });
        // dd($structuredResults);
        return view('user.tournament.results', [
            'heading' => 'Tournament Results',
            'title' => 'Results',
            'active' => 'tournament',
            'tournament' => $tournament,
            'results' => $structuredResults,
        ]);
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

    // request permission for the tournament
    public function permissionPage($id)
    {
        $check_permission = TournamentPermission::where('user_id', Auth::user()->id)->where('tournament_id', $id)->first();
        if (!$check_permission) {
            $status = 'Submit Request';
        }

        $data = [
            'tournament' => Tournament::find($id),
            'status' => $check_permission->status ?? $status,
        ];
        return view('user.tournament.permission', $data);
    }

    public function requestPermission($id)
    {
        $tournament = Tournament::find($id);
        event(new PermissionRequestEvent($tournament->name, Auth::user()->username));
        $permission = TournamentPermission::create([
            'user_id' => Auth::user()->id,
            'tournament_id' => $id,
        ]);

        return redirect()->back()->with('success', 'Your request has been submitted to admin');
    }
}
