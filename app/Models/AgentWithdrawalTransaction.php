<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentWithdrawalTransaction extends Model
{
    use HasFactory;
    protected $fillable=['date_time','amount','agent_id','remark','confirmed_by','confrimed_at','reject_by','reject_at','status'];

    public function agent(){
        return $this->belongsTo(Agent::class);
    }
  
}
