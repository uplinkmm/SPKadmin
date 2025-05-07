<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\Game;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GameSetting extends Model
{
    use HasFactory;
    protected $fillable=[
        'name',
        'opening_time',
        'closing_time',
        'lottery_time',
        'bet_multiplier',
        'twist_multiplier',
        'closing_amount',
        'min',
        'max',
        'time_status',
        'game_id',
        'is_active',
        'opening_date_time',
        'closing_date_time',
        'lottery_date_time',
        'limitation_quantity',
        'price',
        'description',
        'terms_and_condition',
        'photo',
    ];
    public function scopeCurrentTimeBetween($query)
    {
        $now = Carbon::now();
        return $query->where('opening_date_time', '<=', $now)
                     ->whereDate('closing_date_time', '>=', $now);
    }
    public function game(){
        return $this->belongsTo(Game::class);
    }

    public function prizes(){
        return $this->hasMany(Prize::class);
    }
}
