<?php

namespace App\Models;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GamePromotion extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'start_date',
        'end_date',
        'deposit_amount',
        'promotion_percentage',
        'is_active',
        'promotion_amount'
    ];

    protected static function booted()
    {
        static::saving(function ($promotion) {
            $promotion->promotion_amount =
                $promotion->deposit_amount * ($promotion->promotion_percentage / 100);
        });
    }
    public function customers()
    {
        return $this->morphToMany(Customer::class, 'promotion', 'customer_promotions');
    }
}
