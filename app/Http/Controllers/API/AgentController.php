<?php

namespace App\Http\Controllers\API;

use App\Models\Agent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Agent\AgentInterface;

class AgentController extends Controller
{
    //
    private $agentRepo;
    public function __construct(AgentInterface $repo)
    {
        $this->agentRepo=$repo;
    }

    public function index(Request $request){
        $data=$this->agentRepo->list($request);
        ResponseData($data);
    }

    public function show(Agent $agent){
        $data=$this->agentRepo->detail($agent);
        ResponseData($data);
    }

    public function store(Request $request){
        $data=$this->agentRepo->updateOrCreate($request);
        ResponseData($data);
    }
    public function toggleIsActive(Request $request)
    {
        $data = $this->agentRepo->toggleIsActive($request);
        ResponseData($data);
    }

    public function customerListByAgent(Request $request){
        $data = $this->agentRepo->customerListByAgent($request);
        ResponseData($data);
    }

    public function transactionListByAgent(Request $request){
        $data = $this->agentRepo->transactionListByAgent($request);
        ResponseData($data);
    }

    public function commissionAmountOfDayByAgent(Request $request){
        $data = $this->agentRepo->commissionAmountOfDayByAgent($request);
        ResponseData($data);
    }

    public function getAgentWallet(Request $request){
        $data = $this->agentRepo->getAgentWallet($request);
        ResponseData($data);
    }
}
