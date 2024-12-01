<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Models\GameSetting;

class Game extends Model
{
    use HasFactory;
    protected $fillable=['name','type','is_active'];

    public function gameSetting()
    {
        return $this->hasMany(GameSetting::class);
    }
    public function scopeWithTimeStatus($query, $timeStatus) #two_d setting
    {
        return $query->with(['gameSettings' => function ($query) use ($timeStatus) {
            $query->where('time_status', $timeStatus);
        }]);
    }
    public function scopeWithThreedSetting($query, $timeStatus,$game_id)
    {
        return $query->with(['threeDSetting' => function ($query) use ($game_id) {
            $query->where('game_id', $game_id);
        }]);
    }
    public function twodSettings()
    {
        return $this->hasMany(GameSetting::class)
        ->select(['id','name','opening_time','closing_time','lottery_time','bet_multiplier','closing_amount','time_status','game_id','is_active'])
        ->orderBy('lottery_time','asc');
    }

    public function gameSettings()
    {
        return $this->hasOne(GameSetting::class)
        // ->currentTimeBetween()
        ->select(['id','opening_time','closing_time','lottery_time','bet_multiplier','twist_multiplier','closing_amount','time_status','game_id','is_active']);
    }
    public function threedSetting()
    {
        return $this->hasOne(GameSetting::class)->orderBy('id','desc')
        ->currentTimeBetween()
        ->select(['id','opening_date_time','closing_date_time','lottery_date_time','time_status','bet_multiplier','twist_multiplier','closing_amount','game_id','is_active']);
    }
    // protected $appends = ['closing_time', 'fix_time', 'time_status'];

    // // Define the accessor for closing_time
    // public function getClosingTimeAttribute()
    // {
    //     $now = Carbon::now();
    //     $morning_start = $now->copy()->setTime(9, 0, 0);
    //     $morning_end = $now->copy()->setTime(12, 0, 0);
    //     $afternoon_start = $now->copy()->setTime(12, 1, 0);
    //     $afternoon_end = $now->copy()->setTime(16, 30, 0);
    //     if ($now->between($morning_start, $morning_end)) {
    //         return $now->copy()->setTime(11, 30, 0)->toDateTimeString();
    //     } elseif ($now->between($afternoon_start, $afternoon_end)) {
    //         return $now->copy()->setTime(16, 0, 0)->toDateTimeString();
    //     } else {
    //         return $now->copy()->setTime(0, 0, 0)->toDateTimeString();
    //     }
    // }

    // // Define the accessor for fix_time
    // public function getFixTimeAttribute()
    // {
    //     $now = Carbon::now();
    //     $morning_start = $now->copy()->setTime(9, 0, 0);
    //     $morning_end = $now->copy()->setTime(12, 0, 0);
    //     $afternoon_start = $now->copy()->setTime(12, 1, 0);
    //     $afternoon_end = $now->copy()->setTime(16, 30, 0);

    //     if ($now->between($morning_start, $morning_end)) {
    //         return $now->copy()->setTime(12, 1, 0)->toDateTimeString();
    //     } elseif ($now->between($afternoon_start, $afternoon_end)) {
    //         return $now->copy()->setTime(16, 31, 0)->toDateTimeString();
    //     } else {
    //         return null;
    //     }
    // }

    // // Define the accessor for time_status
    // public function getTimeStatusAttribute()
    // {
    //     $now = Carbon::now();
    //     $morning_start = $now->copy()->setTime(9, 0, 0);
    //     $morning_end = $now->copy()->setTime(12, 0, 0);
    //     $afternoon_start = $now->copy()->setTime(12, 1, 0);
    //     $afternoon_end = $now->copy()->setTime(16, 30, 0);

    //     if ($now->between($morning_start, $morning_end)) {
    //         return 'morning';
    //     } elseif ($now->between($afternoon_start, $afternoon_end)) {
    //         return 'evening';
    //     } else {
    //         return null;
    //     }
    // }

    // // Example of boot method to apply the logic automatically
    // protected static function boot()
    // {
    //     parent::boot();

    //     static::retrieved(function ($game) {
    //         $now = Carbon::now();
    //         $morning_start = $now->copy()->setTime(9, 0, 0);
    //         $morning_end = $now->copy()->setTime(12, 0, 0);
    //         $afternoon_start = $now->copy()->setTime(12, 1, 0);
    //         $afternoon_end = $now->copy()->setTime(16, 30, 0);

    //         if ($now->between($morning_start, $morning_end)) {
    //             $time_status = 'morning';
    //             $fix_time = $now->copy()->setTime(12, 1, 0)->toDateTimeString();
    //             $closing_time = $now->copy()->setTime(11, 30, 0)->toDateTimeString();
    //         } elseif ($now->between($afternoon_start, $afternoon_end)) {
    //             $time_status = 'evening';
    //             $fix_time = $now->copy()->setTime(16, 31, 0)->toDateTimeString();
    //             $closing_time = $now->copy()->setTime(16, 0, 0)->toDateTimeString();
    //         } else {
    //             $closing_time = $now->copy()->setTime(0, 0, 0)->toDateTimeString();
    //             $fix_time = null;
    //             $time_status = null;
    //         }

    //         $game->time_status = $time_status;
    //         $game->fix_time = $fix_time;
    //         $game->closing_time = $closing_time;
    //     });
    // }
}
