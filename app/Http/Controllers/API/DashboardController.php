<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\Dashboard\DashboardInterface;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //
    private $dashboardRepo;
    public function __construct(DashboardInterface $repo)
    {
        $this->dashboardRepo=$repo;
    }
    public function getDashboardCRN(Request $request){
        $data=$this->dashboardRepo->getDashboardCRN($request);
        ResponseData($data);
    }

    public function getFinancialReport(Request $request){
        $data=$this->dashboardRepo->getFinancialReport($request);
        ResponseData($data);
    }

    public function updateDashboardData(Request $request){
        $data=$this->dashboardRepo->updateDashboardData($request);
        ResponseData($data);
    }

   
}
