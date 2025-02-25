<?php
namespace App\Traits;

use App\Models\CustomerWallet;
use App\Models\CustomerGameWallet;

trait BuildWallet{

    public function createGameWallet($customerId){
        if (CustomerGameWallet::where('customer_id', $customerId)->first()) {
            // point bag already exists for customer
            return null;
        }

        $wallet = CustomerGameWallet::create([
            'customer_id' => $customerId,
            'promtion_balance'=>0,
            'game_balance'=>0,
            'in_balance'=>0,
            'out_balance'=>0,
            'balance'=>0,
        ]);
        return $wallet;
    }

    public function createWallet($customerId)
    {
        if (CustomerWallet::where('customer_id', $customerId)->first()) {
            // point bag already exists for customer
            return null;
        }
        $prefix = null;
        if ($customerId < 10) {
            $prefix = sprintf('WL000%d', $customerId);
        } else if ($customerId > 10 && $customerId < 100) {
            $prefix = sprintf('WL00%d', $customerId);
        } else if ($customerId > 100 && $customerId < 1000) {
            $prefix = sprintf('WL0%d', $customerId);
        } else {
            $prefix = 'WL' . $customerId;
        }
        $walletId = $prefix . now()->format('Ymd');

        $wallet = CustomerWallet::create([
            'customer_id' => $customerId,
            'walletId' => $walletId,
            'balance' => 0,
        ]);

        return $wallet;
    }
}
