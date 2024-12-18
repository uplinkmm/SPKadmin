<?php

namespace App\Models;

use Bavix\Wallet\Models\Wallet as ModelsWallet;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends ModelsWallet
{
    use HasFactory;
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
