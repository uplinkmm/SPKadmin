<?php
namespace App\Http\Action;

use Illuminate\Support\Str;
use App\Models\WalletTransaction;

trait WalletTransactionCommon 
{

    public function actionOfWalletTransaction($data,$amount,$action){
        $morphMapName = RelationMorphName( $data);
        $bettingTransaction=WalletTransaction::create([
            'date_time'=>now(),
            'amount'=>(int)$amount,
            'walletable_id'=>$data->id,
            'walletable_type'=>$morphMapName,
            'action'=>$action,
            'customer_id'=>$data->customer_id,
        ]);
    }

}