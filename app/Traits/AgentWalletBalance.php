<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait AgentWalletBalance
{

    public function retrieveAgentBalance($agentId){
        $agentWallet = DB::table('agent_wallets')
        ->select(
            DB::raw('SUM(CASE WHEN action = "in" THEN amount ELSE 0 END) as total_in_amount'),
            DB::raw('SUM(CASE WHEN action = "out" THEN amount ELSE 0 END) as total_out_amount'),
            DB::raw('SUM(CASE WHEN action = "in" THEN amount ELSE 0 END) - SUM(CASE WHEN action = "out" THEN amount ELSE 0 END) as total_amount')
        )
        ->where('agent_id', $agentId)
        ->first();
        return $agentWallet ? $agentWallet->total_amount : 0;
    }
}
