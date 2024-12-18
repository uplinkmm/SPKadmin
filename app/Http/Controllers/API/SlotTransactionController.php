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

}
