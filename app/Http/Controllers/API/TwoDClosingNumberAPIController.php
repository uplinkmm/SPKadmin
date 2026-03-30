<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Models\GameSetting;

use App\Repositories\TwoDClosingNumber\TwoDClosingNumberRepositoryInterface;

class TwoDClosingNumberAPIController extends Controller
{
    private $closingNumberRepo;

    public function __construct(TwoDClosingNumberRepositoryInterface $repo)
    {
        $this->closingNumberRepo = $repo;
    }

    public function list(Request $request)
    {
        // if(!$request->date || !$request->game_setting_id){
        //     ResponseMessage('Date and time status must be provided', 400);
        // }
        // $gameSetting = GameSetting::find($request->game_setting_id);
        $data=$this->closingNumberRepo->listClosingNumbers($request->game_setting_id);
        ResponseData($data);
    }

    public function create(Request $request)
    {
        // if(!$request->number || !$request->amount || !$request->game_setting_id){
        //     ResponseMessage('Number, amount and time status must be present', 400);
        // }
        // $gameSetting = GameSetting::find($request->game_setting_id);
        // $data = $request->except('number');
        // $data['game_id'] = $request->game_id;
        // $data['created_by'] = ApiUser()->id;
        // // $data['time_status'] = (date_create($gameSetting->lottery_time)->format('A') == 'AM')? 'morning': 'evening';
        // $numbers = explode(',', $request->number);
        $closingNumber = $this->closingNumberRepo->createClosingNumber($request);
        ResponseData($closingNumber);
    }

    public function setInactive(Request $request)
    {
        if(!$request->number || !$request->game_setting_id){
            ResponseMessage('Number and game setting must be present', 400);
        }
        // $gameSetting = GameSetting::find($request->game_setting_id);
        // $numbers = explode(',', $request->number);

        $this->closingNumberRepo->setInactiveClosingNumber($request);
    }
}
