<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Result;
use App\Models\Round;
use App\Models\Tournament;
use Illuminate\Http\Request;
use Intervention\Image\Colors\Rgb\Channels\Red;

class TournamentController extends Controller
{
    public function index()
    {
        $data = [
            'heading' => 'Tournaments',
            'title' => 'Tournaments',
            'active' => 'tournament',
            'tournaments' => Tournament::orderBy('date', 'desc')->get(),
        ];
        return view('admin.tournament.index', $data);
    }

    public function create()
    {
        $data = [
            'heading' => 'Tournaments',
            'title' => 'Add Tournament',
            'active' => 'tournament',
            'games' => Game::all(),
        ];

        return view('admin.tournament.create', $data);
    }

    public function store(Request $request)
    {
        $tournament = Tournament::create([
            'name' => $request->name,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'time_to_enter' => $request->time_to_enter ?? null,
            'open_close' => $request->open_close,
            'time_or_free' => $request->time_or_free,
            'elimination_type' => $request->elimination_type,
            'elimination_percent' => $request->elimination_percent,
            'status' => $request->status,
            'rounds' => is_array($request->game_id) ? count($request->game_id) : 0,
        ]);

        foreach ($request->game_id as $key => $game_id) {
            Round::create([
                'sequence' => $key + 1,
                'tournament_id' => $tournament->id,
                'game_id' => $game_id,
                'start_time' => $request->round_start_time[$key] ?? null,
                'end_time' => $request->round_end_time[$key] ?? null,
            ]);
        }

        return redirect()->back()->with('success', 'Tournament scheduled successfully');
    }

    public function edit($id)
    {
        $data = [
            'heading' => 'Edit Tournament',
            'title' => 'Edit Tournament',
            'active' => 'tournament',
            'tournament' => Tournament::find($id),
            'games' => Game::all(),
        ];
        return view('admin.tournament.edit', $data);
    }

    public function update(Request $request)
    {
        $tournament = Tournament::findOrFail($request->id);

        // Update tournament details
        $tournament->update([
            'name' => $request->name,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'time_to_enter' => $request->time_to_enter,
            'open_close' => $request->open_close,
            'time_or_free' => $request->time_or_free,
            'elimination_type' => $request->elimination_type,
            'elimination_percent' => $request->elimination_type === 'percentage' ? $request->elimination_percent : null,
            'status' => $request->status,
            'rounds' => count($request->game_id),
        ]);

        // Remove old rounds
        $tournament->tournament_rounds()->delete();

        // Insert new rounds
        foreach ($request->game_id as $key => $game_id) {
            $tournament->tournament_rounds()->create([
                'sequence' => $key + 1,
                'game_id' => $game_id,
                'start_time' => $request->round_start_time[$key] ?? null,
                'end_time' => $request->round_end_time[$key] ?? null,
            ]);
        }

        return redirect()
            ->route('admin.tournament')
            ->with('success', 'Tournament updated successfully');
    }

    public function delete($id)
    {
        $tournament = Tournament::find($id);
        $tournament->delete();
        return redirect()->back()->with('success', 'Tournament Deleted Successfully');
    }

    public function details($id)
    {
        $data = [
            'heading' => 'View Tournament',
            'title' => 'Tournament',
            'active' => 'tournament',
            'tournament' => Tournament::find($id),
            'games' => Game::all(),
        ];
        return view('admin.tournament.details', $data);
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
        return view('admin.tournament.results', [
            'heading' => 'Tournament Results',
            'title' => 'Results',
            'active' => 'tournament',
            'tournament' => $tournament,
            'results' => $structuredResults,
        ]);
    }
}
