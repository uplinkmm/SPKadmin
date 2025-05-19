<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Permission;
use Bavix\Wallet\Interfaces\Wallet;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Bavix\Wallet\Traits\HasWalletFloat;

class User extends Authenticatable implements Wallet
{
    use HasApiTokens, HasFactory, Notifiable,HasWalletFloat;

    protected $guard = 'web';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'password',
        'is_active',
        'phone_number',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
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

    public function routeNotificationForFcm()
    {
        return $this->personTokens()->pluck('fcm_token')->toArray();
    }

    // public function routeNotificationForFcm()
    // {
    //     return $this->fcm_token;
    // }

    public function permissions(){
        return $this->belongsToMany(Permission::class,'user_permission');
    }

    public static function adminUser()
    {
        return self::where('id', 1)->first();
    }

    public function isSuperAdmin()
    {
        return $this->role === 'super-admin';
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }
    public function personTokens()
    {
        return $this->morphMany(PersonFcmToken::class, 'personable');
    }
}
