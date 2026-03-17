<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;
use App\Models\GameLevel;

class PlayGameController extends Controller
{
    public function index($id)
    {
       $game = GameLevel::find($id);
    
         if (!$game) {
              return redirect('/')->with('error', 'Game not found.');
         }

        return view('general_games.' . $game->level_slug, ['game' => $game]);
    }
}
