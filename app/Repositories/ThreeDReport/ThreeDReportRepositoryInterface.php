<?php

namespace App\Repositories\ThreeDReport;

use App\Models\GameSetting;

interface ThreeDReportRepositoryInterface
{
    public function getBettingNumbersWithTotalAmount(int $gameSettingId, int $page);

    public function getBettingNumberCounts($request);

    public function getBettingAmountsByCustomer($request);

    public function getBettingCustomers($request);

    public function getBingoCustomers($request);
}
