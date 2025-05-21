<?php

namespace App\Repositories\LiveData;

use Illuminate\Http\Request;

interface DreamNumberRepositoryInterface
{
    public function bulkCreate(array $dreamNumbers);

    public function getDreamNumbers(Request $request);
}
