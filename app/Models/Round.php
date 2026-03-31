<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Round extends Model
{
    use HasFactory;
    protected $fillable=[
        'sequence',
        'tournament_id',
        'game_id',
        'game_level_id',
        'start_time',
        'end_time',
    ];

    public function get_game(){
        return $this->belongsTo(Game::class,'game_id');
    }

    public function gameLevel()
    {
        return $this->belongsTo(GameLevel::class, 'game_level_id');
    }

    /**
     * Prefer the configured level (if any), else fallback to game.
     */
    public function resolvedGame(): ?Game
    {
        return $this->gameLevel?->game ?? $this->get_game;
    }
}
