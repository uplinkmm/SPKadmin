<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Models\Game;

use App\Repositories\ThreeDReport\ThreeDReportRepositoryInterface;

class ThreeDReportAPIController extends Controller
{
    //
    private $threeDReportRepo;

    public function __construct(ThreeDReportRepositoryInterface $repo)
    {
        $this->threeDReportRepo = $repo;
    }

    public function getSummaryReport(Request $request)
    {
        $game = Game::with('threedSetting')->find(2);
        if(!$game || !$game->threedSetting){
            ResponseMessage('No data can be found', 500);
        }

        $report = $this->threeDReportRepo->getBettingNumbersWithTotalAmount((int) $game->threedSetting->id, (int) $request->page);
        ResponseData($report);
    }

    public function getDetailReport(Request $request)
    {
        // dd($game->threedSetting);
        // if(!$game || !$game->threedSetting){
        //     ResponseMessage('No data can be found', 500);
        // }
        $report = $this->threeDReportRepo->getBettingNumberCounts($request);
        ResponseData($report);
    }

    public function getCustomerWithBetAmounts(Request $request)
    {
        // $game = Game::with('threedSetting')->find(2);
        // if(!$game || !$game->threedSetting){
        //     ResponseMessage('No data can be found', 500);
        // }

        // $gameSetting = $game->threedSetting;

        $report = $this->threeDReportRepo->getBettingAmountsByCustomer($request);

        ResponseData($report);
    }

    public function getCustomerBetList(Request $request)
    {
        // $game = Game::with('threedSetting')->find(2);
        // if(!$game || !$game->threedSetting){
        //     ResponseMessage('No data can be found', 500);
        // }

        // $gameSetting = $game->threedSetting;

        $report = $this->threeDReportRepo->getBettingCustomers($request);

        ResponseData($report);
    }

    public function getBingoCustomers(Request $request)
    {
        // $game = Game::with('threedSetting')->find(2);
        // if(!$game || !$game->threedSetting){
        //     ResponseMessage('No data can be found', 500);
        // }

        // $gameSetting = $game->threedSetting;

        $report = $this->threeDReportRepo->getBingoCustomers($request);
        ResponseData($report);
    }
}
