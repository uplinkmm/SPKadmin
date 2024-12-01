<?php
namespace App\Http\Action;
class Common{

    public function getWalletId($customerId){
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
        return $walletId;
    }
}