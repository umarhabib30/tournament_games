<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tournament_id',
        'round_id',
        'game_id',
        'score',
        'time_taken',
        'status',
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function game(){
        return $this->belongsTo(Game::class, 'game_id');
    }

    public function round(){
        return $this->belongsTo(Round::class, 'round_id');
    }
}
