<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestGameController extends Controller
{
    public function testingGame()
    {
        return view('testing.game');
    }
}
