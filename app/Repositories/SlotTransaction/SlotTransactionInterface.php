<?php

namespace App\Repositories\SlotTransaction;

use Illuminate\Http\Request;

interface SlotTransactionInterface
{
    public function index($request);
    public function slotProviderReport($request);

    public function slotUserReport($request);

    public function slotUserList($request);

}