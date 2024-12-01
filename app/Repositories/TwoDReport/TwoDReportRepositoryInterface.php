<?php

namespace App\Repositories\TwoDReport;

use App\Models\GameSetting;

interface TwoDReportRepositoryInterface
{
    public function getBettingNumbersWithTotalAmount($request);

    public function getBettingNumberCounts(GameSetting $gameSetting, $request);

    public function getBettingAmountsByCustomer(GameSetting $gameSetting, $request);

    public function getBettingCustomers(GameSetting $gameSetting,$request);

    public function getBingoCustomers($request);
}
