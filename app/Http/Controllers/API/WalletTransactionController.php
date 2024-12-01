<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\WalletTransaction\WalletTransactionInterface;

class WalletTransactionController extends Controller
{
    //
    private $walletRepo;
    public function __construct(WalletTransactionInterface $repo){
        $this->walletRepo=$repo;
    }

    public function getBalanceTransaction(Request $request){
        $data=$this->walletRepo->getBalanceTransaction($request);
        ResponseData($data);
    }
}
