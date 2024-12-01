<?php

namespace App\Repositories\TopupTransaction;

use Illuminate\Http\Request;

use App\Models\TopupTransaction;

interface TopupTransactionRepositoryInterface
{
    public function listTransactions(Request $request);

    public function confirmTransaction(TopupTransaction $transaction, $userId);

    public function rejectTransaction(TopupTransaction $transaction, $userId);

    public function getTopupTransactionHistory($request); 

    public function store($request); 

}
