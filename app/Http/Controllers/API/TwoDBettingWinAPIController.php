<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Models\BettingWin;
use App\Models\GameSetting;

use App\Repositories\BettingWin\TwoDBettingWinRepositoryInterface;

class TwoDBettingWinAPIController extends Controller
{
    //
    private $bettingWinRepo;

    public function __construct(TwoDBettingWinRepositoryInterface $repo)
    {
        $this->bettingWinRepo = $repo;
    }

    public function list(Request $request)
    {
        $this->bettingWinRepo->listBettingWins($request);
    }

    public function create(Request $request)
    {
        if((!$request->number || !$request->game_setting_id) || ($request->number== 'null' || $request->game_setting_id=='null')){
            ResponseMessage('Number and game setting id must be present', 400);
        }

        if(!$this->isValidTwoDigitNumber($request->number)){
            ResponseMessage('Two D number is not correct', 400);
        }

        $gameSetting = GameSetting::find($request->game_setting_id);

        $data = $request->all();
        $data['date_time'] = CurrentTime();
        $data['created_by'] = ApiUser()->id;
        $data['game_setting_id'] = $gameSetting->id;
        $data['time_status'] = (date_create($gameSetting->closing_time)->format('A') == 'AM')? 'morning': 'evening';

        $bettingWin = $this->bettingWinRepo->createBettingWin($data);

        ResponseData($bettingWin);
    }

    public function update(Request $request, BettingWin $bettingWin)
    {
        if($bettingWin->is_approved == 1){
            ResponseMessage('Number already approved for winning', 400);
        }
        if(!$request->number || $request->number=='null'){
            ResponseMessage('Number must be present', 400);
        }

        if(!$this->isValidTwoDigitNumber($request->number)){
            ResponseMessage('Two D number is not correct', 400);
        }
        $data = $request->all();
        $data['date_time'] = CurrentTime();
        $data['created_by'] = ApiUser()->id;

        $bettingWin = $this->bettingWinRepo->upateBettingWin($data, $bettingWin);

        ResponseData($bettingWin);
    }

    public function approve(Request $request, BettingWin $bettingWin)
    {
        $this->bettingWinRepo->approveBettingWin($bettingWin);
    }

    private function isValidTwoDigitNumber($str) {
        return preg_match('/^\d{2}$/', $str) === 1;
    }
}
