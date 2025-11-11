<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TournamentPermission extends Model
{
    use HasFactory;

    protected $fillable=[
        'tournament_id', 'user_id', 'status',
    ];

    public function tournament(){
        return $this->belongsTo(Tournament::class, 'tournament_id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
