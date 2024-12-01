<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authencticatable;

use Laravel\Sanctum\HasApiTokens;

class Agent extends Authencticatable
{
    use HasFactory,HasApiTokens;
    protected $guard = 'agent';
    protected $fillable=['name','code','phone_number','password','is_active'];


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


    public function agent_commissions(){
        return $this->hasMany(AgentCommission::class);
    }

}
