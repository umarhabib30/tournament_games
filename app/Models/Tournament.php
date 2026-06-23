<?php

namespace App\Models;

use Carbon\Carbon;
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
        'results_published',
        'results_published_at',
    ];

    protected $casts = [
        'results_published' => 'boolean',
        'results_published_at' => 'datetime',
    ];

    public function tournamentTimezone(): string
    {
        return config('app.timezone') ?: 'Asia/Karachi';
    }

    public function hasEnded(): bool
    {
        if (!$this->date || !$this->end_time) {
            return false;
        }

        $tz = $this->tournamentTimezone();
        $now = Carbon::now($tz)->copy()->seconds(0)->milliseconds(0);
        $endTime = Carbon::parse($this->date . ' ' . $this->end_time, $tz)
            ->copy()
            ->seconds(0)
            ->milliseconds(0);

        return $now->greaterThanOrEqualTo($endTime);
    }

    public function canPublishResults(): bool
    {
        return $this->hasEnded() && !$this->results_published;
    }


    public function tournament_rounds()
    {
        return $this->hasMany(Round::class, 'tournament_id', 'id');
    }
}
