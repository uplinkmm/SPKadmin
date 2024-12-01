<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerGameWallet extends Model
{
    use HasFactory;
    protected $fillable=['customer_id','promotion_balance','game_balance','in_balance','out_balance','balance'];
}
