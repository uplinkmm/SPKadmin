<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Models\GameSetting;

use App\Repositories\TwoDReport\TwoDReportRepositoryInterface;

class TwoDBingoAPIController extends Controller
{
    //
    private $twoDReportRepo;

    public function __construct(TwoDReportRepositoryInterface $repo)
    {
        $this->twoDReportRepo = $repo;
    }

    public function getBingoCustomers(Request $request)
    {
        if(!$request->game_setting_id){
            ResponseMessage('Date and game setting must be provided', 400);
        }
        $report = $this->twoDReportRepo->getBingoCustomers($request);

        ResponseData($report);
    }
}
