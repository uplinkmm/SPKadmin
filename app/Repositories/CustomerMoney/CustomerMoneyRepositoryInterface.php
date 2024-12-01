<?php

namespace App\Repositories\CustomerMoney;

use App\Models\Customer;
use App\Models\CustomerPointBag;
use App\Models\CustomerWallet;

interface CustomerMoneyRepositoryInterface
{
    public function createPointBag(int $customerId);

    public function createWallet($customerId);

    public function moneyBalances($customerId);
}
