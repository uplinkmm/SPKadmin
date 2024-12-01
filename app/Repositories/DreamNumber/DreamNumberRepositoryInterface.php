<?php

namespace App\Repositories\DreamNumber;

use Illuminate\Http\Request;

interface DreamNumberRepositoryInterface
{
    public function bulkCreate(array $dreamNumbers);

    public function getDreamNumbers(Request $request);
}
