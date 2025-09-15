<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LotteryNumber extends Model
{
    use HasFactory;
    protected $fillable = ['number', 'amount', 'lottery_id', 'game_setting_id', 'lottery_winning_number_id'];

    public function lottery()
    {
        return $this->belongsTo(Lottery::class);
    }

}
