<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LotteryPromotion extends Model
{
    use HasFactory;
    protected $fillable=['game_setting_id','start_date','end_date','is_active'];
    public function game_setting(){
        return $this->belongsTo(GameSetting::class);
    }

}
