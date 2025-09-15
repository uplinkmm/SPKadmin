<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LotteryBettingPromotion extends Model
{
    use HasFactory;
    protected $fillable=['lottery_id','lottery_promotion_ticket_id'];

}
