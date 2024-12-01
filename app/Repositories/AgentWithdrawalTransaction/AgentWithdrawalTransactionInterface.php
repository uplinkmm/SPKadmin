<?php

namespace App\Repositories\AgentWithdrawalTransaction;

interface AgentWithdrawalTransactionInterface
{
    public function create($request);
    public function list($request);
    public function updateAgentWalletTransactionStatus($request);
}
