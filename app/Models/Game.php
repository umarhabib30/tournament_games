<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;
     protected $fillable =[
        'title',
        'image',
        'description',
        'rules',
        'status',
        'slug',
    ];

    public function levels()
    {
        return $this->hasMany(GameLevel::class, 'game_id', 'id');
    }
}
