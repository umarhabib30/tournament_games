<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;

class PlayGameController extends Controller
{
    public function index($id)
    {
       $game = Game::find($id);
         if (!$game) {
              return redirect('/')->with('error', 'Game not found.');
         }

        return view('general_games.' . $game->slug, ['game' => $game]);
    }
}
