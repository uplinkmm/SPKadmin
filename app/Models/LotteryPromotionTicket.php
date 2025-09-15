<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LotteryPromotionTicket extends Model
{
    use HasFactory;
    protected $fillable=['qty','additional_qty','lottery_promotion_id'];
    public function lottery_promotion(){
        return $this->belongsTo(LotteryPromotion::class);
    }
  
}
