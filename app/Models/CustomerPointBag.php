<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Customer;

class CustomerPointBag extends Model
{
    protected $fillable = [
        'customer_id',
        'bagId',
        'balance'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
