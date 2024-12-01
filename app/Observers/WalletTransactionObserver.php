<?php
namespace App\Observers;

use App\Http\Action\Common;
use App\Models\CustomerWallet;
use App\Models\WalletTransaction;

class WalletTransactionObserver
{
    public function created(WalletTransaction $transaction)
    {
        $customerWallet = CustomerWallet::firstOrNew(['customer_id' => $transaction->customer_id]);
        $customerWallet->walletId=(new Common())->getWalletId($transaction->customer_id);
        if ($transaction->action == 'in') {
            $customerWallet->in_balance += (double)$transaction->amount;
        } elseif ($transaction->action == 'out') {
            $customerWallet->out_balance += (double)$transaction->amount;
        }
        $customerWallet->balance = $customerWallet->in_balance - $customerWallet->out_balance;
        $customerWallet->save();
    }
}