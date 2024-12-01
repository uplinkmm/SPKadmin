<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\TopupTransaction;

use App\Repositories\TopupTransaction\TopupTransactionRepositoryInterface;

class TopupTransactionAPIController extends Controller
{
    //
    private $topupRepo;

    public function __construct(TopupTransactionRepositoryInterface $repo)
    {
        $this->topupRepo = $repo;
    }

    public function getTransactionList(Request $request)
    {
        $transactions = $this->topupRepo->listTransactions($request);

        ResponseData($transactions);
    }

    public function store(Request $request)
    {
        $transactions = $this->topupRepo->store($request);

        ResponseData($transactions);
    }

    public function confirmOrRejectTransaction(Request $request, TopupTransaction $transaction)
    {
        if($transaction->status != 'pending'){
            ResponseMessage('Topup transaction already handled', 400);
        }
        $userId = ApiUser()->id;
        if($request->handle_type == 'confirm'){
            if($this->topupRepo->confirmTransaction($transaction, $userId)){
                ResponseMessage('Topup transaction confirmed');
            }
            else{
                ResponseMessage('Topup transaction confirmation failed', 500);
            }
        }
        if($request->handle_type == 'reject'){
            if($this->topupRepo->rejectTransaction($transaction, $userId)){
                ResponseMessage('Topup transaction rejected');
            }
            else{
                ResponseMessage('Topup transaction rejection failed', 500);
            }
        }
    }

    
    public function getTopupTransactionHistory(Request $request){
        $data = $this->topupRepo->getTopupTransactionHistory($request);
        ResponseData($data);
    }
}
