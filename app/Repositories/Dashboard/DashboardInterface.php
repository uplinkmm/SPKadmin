<?php

namespace App\Repositories\Dashboard;

interface DashboardInterface
{

    public function getDashboardCRN($request);

    public function getFinancialReport($request);
    
    public function updateDashboardData($request);

    public function getCustomerList($request);

    public function getCustomerLimitationList($request);
}
