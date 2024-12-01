<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Customer;

class CustomerWallet extends Model
{
    protected $fillable = [
        'customer_id',
        'walletId',
        'balance',
        'in_balance',
        'out_balance',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
