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
        'start_time',
        'end_time',
    ];

    public function get_game(){
        return $this->belongsTo(Game::class,'game_id');
    }
}
