<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\AgentWithdrawalTransaction\AgentWithdrawalTransactionInterface;
use Illuminate\Http\Request;

class AgentWithdrawalTransactionController extends Controller
{
    //
    private $agentWithdrawal;
    public function __construct(AgentWithdrawalTransactionInterface $repo)
    {
        $this->agentWithdrawal=$repo;
    }
    public function index(Request $request){
        $data=$this->agentWithdrawal->list($request);
        ResponseData($data);
    }

    public function store(Request $request){
        $data=$this->agentWithdrawal->create($request);
        ResponseData($data);
    }

    public function updateAgentWalletTransactionStatus(Request $request){
        $data=$this->agentWithdrawal->updateAgentWalletTransactionStatus($request);
        ResponseData($data);
    }
}
