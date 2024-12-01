<?php

namespace App\Observers;

use App\Models\AgentWallet;
use App\Models\AgentWithdrawalTransaction;

class AgentWithdrawalObserver
{
    /**
     * Handle the AgentWithdrawalTransaction "created" event.
     */
    public function created(AgentWithdrawalTransaction $agentWithdrawalTransaction): void
    {
        //
        // AgentWallet::create([
        //     'date_time'=>now(),
        //     'amount'=>$agentWithdrawalTransaction->amount,
        //     'walletable_id'=>$agentWithdrawalTransaction->id,
        //     'walletable_type'=>'agent_withdrawal_transaction',
        //     'action'=>'out',
        // ]);
    }

    /**
     * Handle the AgentWithdrawalTransaction "updated" event.
     */
    public function updated(AgentWithdrawalTransaction $agentWithdrawalTransaction): void
    {
        //
        if ($agentWithdrawalTransaction->wasChanged('status') &&$agentWithdrawalTransaction->status=='confirmed') {
            AgentWallet::create([
                'date_time' => now(),
                'amount' => $agentWithdrawalTransaction->amount,
                'walletable_id' => $agentWithdrawalTransaction->id,
                'walletable_type' => 'agent_withdrawal_transaction',
                'agent_id'=>$agentWithdrawalTransaction->agent_id,
                'action' => 'out',
            ]);
        }
        
    }

    /**
     * Handle the AgentWithdrawalTransaction "deleted" event.
     */
    public function deleted(AgentWithdrawalTransaction $agentWithdrawalTransaction): void
    {
        //
    }

    /**
     * Handle the AgentWithdrawalTransaction "restored" event.
     */
    public function restored(AgentWithdrawalTransaction $agentWithdrawalTransaction): void
    {
        //
    }

    /**
     * Handle the AgentWithdrawalTransaction "force deleted" event.
     */
    public function forceDeleted(AgentWithdrawalTransaction $agentWithdrawalTransaction): void
    {
        //
    }
}
