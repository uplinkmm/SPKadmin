<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Http\Requests\CashWithdrawalConfirmRequest;

use App\Models\CashWithdrawlTransaction;

use App\Repositories\CashWithdrawlTransaction\CashWithdrawlTransactionRepositoryInterface;

class CashWithdrawlTransactionAPIController extends Controller
{
    //
    private $withdrawlRepo;

    public function __construct(CashWithdrawlTransactionRepositoryInterface $repo)
    {
        $this->withdrawlRepo = $repo;
    }

    public function getTransactionList(Request $request)
    {
        $transactions = $this->withdrawlRepo->listTransactions($request);

        ResponseData($transactions);
    }

    public function store(Request $request)
    {
        $transactions = $this->withdrawlRepo->store($request);

        ResponseData($transactions);
    }

    public function confirmOrRejectTransaction(Request $request, CashWithdrawlTransaction $transaction)
    {
        if($transaction->status != 'pending'){
            ResponseMessage('Cash withdrawl transaction already handled', 400);
        }
        // if($request->handle_type == 'confirm' && !$request->payment_transaction_id){
        //     ResponseMessage('Payment transaction id not present', 400);
        // }
        $userId = ApiUser()->id;
        if($request->handle_type == 'confirm'){
            if($this->withdrawlRepo->confirmTransaction($transaction, $userId)){
                ResponseMessage('Cash withdrawl transaction confirmed');
            }
            else{
                ResponseMessage('Cash withdrawl transaction confirmation failed', 500);
            }
        }
        if($request->handle_type == 'reject'){
            if($this->withdrawlRepo->rejectTransaction($transaction, $userId)){
                ResponseMessage('Cash withdrawl transaction rejected');
            }
            else{
                ResponseMessage('Cash withdrawl transaction rejection failed', 500);
            }
        }
    }

    public function getWithdrawalTransactionHistory(Request $request){
        $data = $this->withdrawlRepo->getWithdrawalTransactionHistory($request);
        ResponseData($data);
    }
}
