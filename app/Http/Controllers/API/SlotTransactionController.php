<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\SlotTransaction\SlotTransactionInterface;
use Illuminate\Http\Request;

class SlotTransactionController extends Controller
{
    //
    private $slotTransactionRepo;
    public function __construct(SlotTransactionInterface $repo)
    {
        $this->slotTransactionRepo=$repo;
    }
    public function index(Request $request){
        $data=$this->slotTransactionRepo->index($request);
        ResponseData($data);
    }

    public function slotProviderReport(Request $request){
        $data=$this->slotTransactionRepo->slotProviderReport($request);
        ResponseData($data);
    }
    public function slotUserReport(Request $request){
        $data=$this->slotTransactionRepo->slotUserReport($request);
        ResponseData($data);
    }
    public function slotUserList(Request $request){
        $data=$this->slotTransactionRepo->slotUserList($request);
        ResponseData($data);
    }

}
