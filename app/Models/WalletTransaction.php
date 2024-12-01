<?php

namespace App\Models;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WalletTransaction extends Model
{
    use HasFactory;
    protected $fillable=['amount','date_time','walletable_type','walletable_id','action','customer_id'];

    public function walletable(){
        return $this->morphTo();
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
