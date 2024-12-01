<?php

namespace App\Repositories\TwoDClosingNumber;

use App\Models\GameSetting;

interface TwoDClosingNumberRepositoryInterface
{
    public function createClosingNumber($request);

    public function setInactiveClosingNumber($request);

    public function listClosingNumbers($gameSettingId);
}
