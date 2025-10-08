<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ImageHelper;
use App\Http\Controllers\Controller;
use App\Models\Game;
use Illuminate\Http\Request;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'heading' => 'Games',
            'title' => 'Games',
            'active' => 'game',
            'games' => Game::all(),
        ];
        return view('admin.game.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = [
            'heading' => 'Games',
            'title' => 'Add Game',
            'active' => 'game'
        ];
        return view('admin.game.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $path = ImageHelper::saveImage($request->image, 'games');
        $game = Game::create([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $path,
            'rules' => json_encode($request->rules),
            'status' => $request->status,
            'slug' => $request->slug,
        ]);
        return redirect()->back()->with('success', 'Game added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $game = Game::find($id);
        $data = [
            'heading' => 'Edit Game',
            'title' => 'Edit Game',
            'active' => 'game',
            'game' => $game
        ];
        return view('admin.game.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $game = Game::findOrFail($request->id);

        // Handle image (replace only if new one uploaded)
        if ($request->hasFile('image')) {
            $path = ImageHelper::saveImage($request->image, 'games');
        } else {
            $path = $game->image; // keep old image
        }

        // Update fields
        $game->update([
            'title'       => $request->title,
            'description' => $request->description,
            'image'       => $path,
            'rules'       => json_encode($request->rules), // save rules as JSON
            'status'      => $request->status,
        ]);

        return redirect()->route('admin.game')->with('success', 'Game updated successfully');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
