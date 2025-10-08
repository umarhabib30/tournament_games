<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tournament extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'date',
        'start_time',
        'end_time',
        'time_to_enter',
        'open_close',  // open or close
        'time_or_free', // rounds has time or has no time limit
        'elimination_type', // eliminate on %age basis or allow all users to play till end of game
        'elimination_percent',
        'rounds',
        'url',
        'status',  // inactive, inprogress, completed
    ];


    public function tournament_rounds()
    {
        return $this->hasMany(Round::class, 'tournament_id', 'id');
    }
}
