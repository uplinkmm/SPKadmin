<?php

namespace App\Repositories\Account;

interface AccountInterface
{
    public function list($request);

    public function detail($account);
    
    public function updateOrCreate($request);

    public function toggleIsActive($request);
}
