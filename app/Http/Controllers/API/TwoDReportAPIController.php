<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Models\Betting;
use App\Models\BettingNumber;
use App\Models\GameSetting;

use App\Repositories\TwoDReport\TwoDReportRepositoryInterface;

class TwoDReportAPIController extends Controller
{
    //
    private $twoDReportRepo;

    public function __construct(TwoDReportRepositoryInterface $repo)
    {
        $this->twoDReportRepo = $repo;
    }

    public function getSummaryReport(Request $request)
    {
        if(!$request->game_setting_id){
            ResponseMessage('Date and game setting must be provided', 400);
        }

        $gameSetting = GameSetting::find($request->game_setting_id);

        $report = $this->twoDReportRepo->getBettingNumbersWithTotalAmount($request);

        ResponseData($report);
    }

    public function getDetailReport(Request $request)
    {
        if(!$request->game_setting_id){
            ResponseMessage('Game setting must be provided', 400);
        }

        $gameSetting = GameSetting::find($request->game_setting_id);

        $report = $this->twoDReportRepo->getBettingNumberCounts($gameSetting, $request);

        ResponseData($report);
    }

    public function getCustomerWithBetAmounts(Request $request)
    {
        if(!$request->game_setting_id){
            ResponseMessage('Game setting must be provided', 400);
        }

        $gameSetting = GameSetting::find($request->game_setting_id);

        $report = $this->twoDReportRepo->getBettingAmountsByCustomer($gameSetting, $request);

        ResponseData($report);
    }

    public function getCustomerBetList(Request $request)
    {
        if(!$request->game_setting_id){
            ResponseMessage(' Game setting must be provided', 400);
        }

        $gameSetting = GameSetting::find($request->game_setting_id);

        $report = $this->twoDReportRepo->getBettingCustomers($gameSetting, $request);

        ResponseData($report);
    }
}
