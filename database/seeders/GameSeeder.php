<?php

namespace Database\Seeders;

use App\Models\Game;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Game::create([
            'title' => 'The Matrix Game',
            'description' => 'Classic arcade game where players defend against waves of aliens.',
            'image' => 'games/matrix_game.png',
            'rules' => json_encode(['Click numbers in ascending order from 1 to 81', 'Correct numbers turn black and are disabled', 'Wrong numbers will shake to indicate error', 'Submit your result anytime to see your progress']),
            'status' => 'active',
            'slug' => 'matrix_round_game',
        ]);

        Game::create([
            'title' => 'Puzzle Quest',
            'description' => 'Match-3 puzzle game with RPG elements.',
            'image' => 'games/color_game.png',
            'rules' => json_encode(['No external aids', 'Complete puzzles within time limit']),
            'status' => 'active',
            'slug' => 'puzzle_quest',
        ]);
    }
}
