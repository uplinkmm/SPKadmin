<?php

namespace App\Models;

use App\Models\Game;
use App\Models\Agent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AgentCommission extends Model
{
    use HasFactory;
    protected $fillable=['agent_id','game_id','commission_amount'];

    protected $with=['game'];

    public function agent(){
        return $this->belongsTo(Agent::class);
    }

    public function game(){
        return $this->belongsTo(Game::class);
    }
}
