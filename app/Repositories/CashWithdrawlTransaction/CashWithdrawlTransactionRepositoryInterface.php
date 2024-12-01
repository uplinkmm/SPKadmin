<?php

namespace App\Repositories\CashWithdrawlTransaction;

use Illuminate\Http\Request;

use App\Models\CashWithdrawlTransaction;

interface CashWithdrawlTransactionRepositoryInterface
{
    public function listTransactions(Request $request);

    public function store( $request);

    public function confirmTransaction(CashWithdrawlTransaction $transaction, $userId);


    public function rejectTransaction(CashWithdrawlTransaction $transaction, $userId);

    public function getWithdrawalTransactionHistory($request); 

}
