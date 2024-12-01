<?php

namespace App\Repositories\CustomerMoney;

use App\Models\Customer;
use App\Models\CustomerPointBag;
use App\Models\CustomerWallet;

class CustomerMoneyRepository implements CustomerMoneyRepositoryInterface
{
    public function createPointBag(int $customerId)
    {
        if(CustomerPointBag::where('customer_id', $customerId)->first()){
            // point bag already exists for customer
            return null;
        }
        $prefix = null;
        if($customerId < 10){
            $prefix = sprintf('PB000%d', $customerId);
        }
        else if($customerId > 10 && $customerId < 100){
            $prefix = sprintf('PB00%d', $customerId);
        }
        else if($customerId > 100 && $customerId < 1000){
            $prefix = sprintf('PB0%d', $customerId);
        }
        else{
            $prefix = 'PB' . $customerId;
        }
        $bagId = $prefix . now()->format('Ymd');

        $pointBag = CustomerPointBag::create([
            'customer_id' => $customerId,
            'bagId' => $bagId,
            'balance' => 0
        ]);

        return $pointBag;
    }

    public function createWallet($customerId)
    {
        if(CustomerWallet::where('customer_id', $customerId)->first()){
            // point bag already exists for customer
            return null;
        }
        $prefix = null;
        if($customerId < 10){
            $prefix = sprintf('WL000%d', $customerId);
        }
        else if($customerId > 10 && $customerId < 100){
            $prefix = sprintf('WL00%d', $customerId);
        }
        else if($customerId > 100 && $customerId < 1000){
            $prefix = sprintf('WL0%d', $customerId);
        }
        else{
            $prefix = 'WL' . $customerId;
        }
        $walletId = $prefix . now()->format('Ymd');

        $wallet = CustomerWallet::create([
            'customer_id' => $customerId,
            'walletId' => $walletId,
            'balance' => 0
        ]);

        return $wallet;
    }

    public function moneyBalances($customerId)
    {
        $pointBag = CustomerPointBag::where('customer_id', $customerId)->first();
        $wallet = CustomerWallet::where('customer_id', $customerId)->first();

        return ['main_money' => $wallet, 'game_money' => $pointBag];
    }
}
