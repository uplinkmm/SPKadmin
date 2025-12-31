<?php

namespace App\Models;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserPromotion extends Model
{
    use HasFactory;
    protected $fillable=['title','start_date','end_date','amount','is_active'];
    public function customers()
    {
        return $this->morphToMany(Customer::class, 'promotion', 'customer_promotions');
    }
}
