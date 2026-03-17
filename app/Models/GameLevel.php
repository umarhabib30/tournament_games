<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameLevel extends Model
{
    use HasFactory;
    protected $fillable =[
        'game_id',
        'level_name',
        'level_description',
        'level_image',
        'level_status',
        'level_slug',
    ];

    public function game()
    {
        return $this->belongsTo(Game::class, 'game_id', 'id');
    }
}
