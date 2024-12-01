<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;
    protected $fillable=['name','phone_number','account_type','color_code','deposit_id','withdrawal_id','is_active'];
    public function deposit(){
        return $this->belongsTo(TransactionType::class,'deposit_id');
    }

    public function withdrawal(){
        return $this->belongsTo(TransactionType::class,'withdrawal_id');
    }
}
