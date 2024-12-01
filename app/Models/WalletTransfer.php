<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletTransfer extends Model
{
    use HasFactory;
    protected $fillable=['customer_id','transfer_type','amount','previous_balance','previous_balance','new_balance'];
}
