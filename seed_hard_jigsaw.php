<?php

$game = \App\Models\Game::where('slug', 'jigsaw_puzzle')->first();
if ($game) {
    \App\Models\GameLevel::firstOrCreate(
        ['level_slug' => 'jigsaw-puzzle-hard'],
        [
            'game_id' => $game->id,
            'level_name' => 'Hard',
            'level_description' => 'Solve the 5x5 jigsaw puzzle!',
            'level_image' => 'games/img1.jpg',
            'level_status' => 'active'
        ]
    );
    echo "Hard level registered successfully!\n";
} else {
    echo "Jigsaw puzzle game not found!\n";
}
