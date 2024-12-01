<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentWallet extends Model
{
    use HasFactory;
    protected $fillable=['date_time','amount','walletable_id','walletable_type','agent_id','action'];

    public function walletable()
    {
        return $this->morphTo();
    }
}
