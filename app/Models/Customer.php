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

class Customer extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
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

    public function wallet()
    {
        return $this->hasOne(CustomerWallet::class);
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

    public function personTokens(){
        return $this->morphMany(PersonFcmToken::class,'personable');
    }
}
