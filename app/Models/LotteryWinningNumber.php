<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LotteryWinningNumber extends Model
{
    use HasFactory;
    protected $fillable = ['prize_id', 'number', 'approved_at', 'approved_by', 'created_by', 'updated_by', 'is_approve', 'lottery_winning_number_id'];

    public function prize()
    {
        return $this->belongsTo(Prize::class);
    }
    public function approved_by()
    {
        return $this->belongsTo(Customer::class,'approved_by');
    }
    public function lottery_number()
    {
        return $this->hasOne(LotteryNumber::class, 'lottery_winning_number_id');
    }
}
