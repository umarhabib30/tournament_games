<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ImageHelper;
use App\Http\Controllers\Controller;
use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\GameLevel;

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
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'rules' => 'required',
            'status' => 'required',
            'slug' => 'required|unique:games,slug',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first())->withInput();
        }
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

    public function levels(string $id)
    {
        $data = [
            'heading' => 'Game Levels',
            'title' => 'Game Levels',
            'active' => 'game',
            'game' => Game::find($id)
        ];
        return view('admin.game.levels', $data);
    }

    public function levelsStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'level_name' => 'required',
            'level_description' => 'required',
            'level_slug' => 'required|unique:game_levels,level_slug',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first())->withInput();
        }
        if ($request->hasFile('level_image')) {
            $path = ImageHelper::saveImage($request->level_image, 'levels');
        } else {
            $path = null;
        }
        $level = GameLevel::create([
            'game_id' => $request->game_id,
            'level_name' => $request->level_name,
            'level_description' => $request->level_description,
            'level_image' => $path,
            'level_slug' => $request->level_slug,
        ]);
        return redirect()->back()->with('success', 'Level added successfully');
    }

    public function levelsEdit(string $id)
    {
        $level = GameLevel::find($id);
        $data = [
            'heading' => 'Edit Level',
            'title' => 'Edit Level',
            'active' => 'game',
            'level' => $level
        ];
        return view('admin.game.edit-level', $data);
    }

    public function levelsUpdate(Request $request)
    {
        $level = GameLevel::findOrFail($request->id);
        if ($request->hasFile('level_image')) {
            $path = ImageHelper::saveImage($request->level_image, 'levels');
        } else {
            $path = $level->level_image;
        }
        $level->update([
            'level_name' => $request->level_name,
            'level_description' => $request->level_description,
            'level_image' => $path,
            'level_status' => $request->level_status,
            'level_slug' => $request->level_slug,
        ]);
        return redirect()->route('admin.game.levels', $request->game_id)->with('success', 'Level updated successfully');
    }

    public function levelsDelete(string $id)
    {
        $level = GameLevel::findOrFail($id);
        $level->delete();
        return redirect()->back()->with('success', 'Level deleted successfully');
    }
}
