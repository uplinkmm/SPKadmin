<?php

namespace App\Models;

use App\Models\Betting;
use App\Models\CustomerWallet;
use App\Models\CustomerPointBag;

use App\Models\TopupTransaction;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Bavix\Wallet\Traits\HasWallet;
use Bavix\Wallet\Traits\HasWalletFloat;
use Bavix\Wallet\Interfaces\Wallet;

class Customer extends Authenticatable implements Wallet
{
    use HasApiTokens, HasFactory, Notifiable, HasWalletFloat;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'user_name',
        'phone_number',
        'password',
        'otp',
        'is_verified',
        'verified_at',
        'email',
        'provider_id',
        'provider_name',
        'email_verified_at',
        'two_d_limit',
        'three_d_limit',
        'agent_id',
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'otp',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
    ];


    protected static function booted()
    {
        // static::creating(function ($customer) {
        //     if (!$customer->user_name) {
        //         // Assign user_name based on user_id
        //         $customer->user_name = 'user_' . $customer->id;
        //     }
        // });
    }

    public function getOtpCode()
    {
        return $this->otp;
    }

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function point_bag()
    {
        return $this->hasOne(CustomerPointBag::class);
    }

    public function customerWallet()
    {
        return $this->hasOne(CustomerWallet::class)->select('customer_id', 'balance')->withDefault([
            'balance' => 0, // Set default values as needed
        ]);
    }
    public function main_wallet()
    {
        return $this->hasOne(CustomerWallet::class)->select('customer_id', 'balance')->withDefault([
            'balance' => 0, // Set default values as needed
        ]);
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public function bettings()
    {
        return $this->hasMany(Betting::class);
    }


    public function topupTransactions()
    {
        return $this->hasMany(TopupTransaction::class);
    }

    public function withdrawalTransactions()
    {
        return $this->hasMany(CashWithdrawlTransaction::class);
    }

    public function routeNotificationForFcm()
    {
        return $this->personTokens()->pluck('fcm_token')->toArray();
    }

    // public function routeNotificationForFcm()
    // {
    //     return $this->fcm_token;
    // }

    public function personTokens()
    {
        return $this->morphMany(PersonFcmToken::class, 'personable');
    }
}
