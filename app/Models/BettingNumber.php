<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BettingNumber extends Model
{
    protected $fillable=['number','amount','bet_id','is_win','betting_multiplier','game_setting_id','is_win',
        'is_twist'];

    public function betting()
    {
        return $this->belongsTo(Betting::class);
    }
}
