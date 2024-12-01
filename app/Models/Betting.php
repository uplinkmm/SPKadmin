<?php

namespace App\Models;

use App\Models\Game;
use App\Models\Customer;
use App\Models\GameSetting;
use App\Models\BettingNumber;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Betting extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable=[
        'date_time',
        'betting_no_id',
        'total_amount',
        'customer_id',
        'game_id',
        'time_status',
        'game_setting_id',
    ];

    public function bettingNumbers(){
        return $this->hasMany(BettingNumber::class);
    }

    
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function game()
    {
        return $this->belongsTo(Game::class);
    }
    public function gameSetting()
    {
        return $this->belongsTo(GameSetting::class);
    }

    
}
