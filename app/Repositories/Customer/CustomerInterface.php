<?php

namespace App\Repositories\Customer;

interface CustomerInterface
{

    public function getCustomerList($request);

    public function getCustomerLimitationList($request);

    public function updateCustomerBetLimit($request);

    public function store($request);

    public function verifyCustomer($request);
}