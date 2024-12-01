<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TopupTransaction extends Model
{
    protected $fillable = [
        'customer_id',
        'transactionId',
        'amount',
        'payment_provider',
        'payment_transaction_id',
        'status',
        'confirmed_by',
        'confirmed_at',
        'rejected_by',
        'rejected_at',
        'remark',
        'account_id',
        'createdable_id',
        'createdable_type',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function confirmedBy()
    {
        return $this->belongsTo(User::class, 'confirmed_by');
    }

    public function rejectedBy()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
