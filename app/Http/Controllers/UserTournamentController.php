<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Result;
use App\Models\Round;
use App\Models\Tournament;
use Carbon\Carbon;
use Illuminate\Http\Request;

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
        $tournament = Tournament::find($id);
        // if tournament time to enter is passed, redirect to play page
        if($tournament->time_to_enter && $tournament->time_to_enter < Carbon::now('Asia/Karachi')){
            return redirect()->back()->with('error', 'Tournament entry time has passed.');
        }
        $data = [
            'tournament' => $tournament,
        ];
        return view('user.tournament.waiting', $data);
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

        $serverNow = now()->timestamp;  // ✅ pass server time for clock skew correction

        if ($tournament->time_or_free === 'time') {
            $endTime = $this->convertTimeToTimestamp($round->end_time);
        } else {
            $endTime = $this->convertTimeToTimestamp($tournament->end_time);
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
}
