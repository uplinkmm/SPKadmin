<?php

namespace App\Repositories\WalletTransaction;

use App\Models\GameSetting;

interface WalletTransactionInterface
{
    public function getBalanceTransaction($request);
}