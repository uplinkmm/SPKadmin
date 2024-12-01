<?php

namespace App\Repositories\Agent;

interface AgentInterface
{
    public function list($request);

    public function updateOrCreate($request);

    public function detail($agent);

    public function toggleIsActive($request);

    public function customerListByAgent($request);

    public function transactionListByAgent($request);
    
    public function commissionAmountOfDayByAgent( $request);

    public function getAgentWallet($request);
}
