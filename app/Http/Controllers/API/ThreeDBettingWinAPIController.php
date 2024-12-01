<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Models\BettingWin;

use App\Repositories\BettingWin\ThreeDBettingWinRepositoryInterface;

class ThreeDBettingWinAPIController extends Controller
{
    //
    private $bettingWinRepo;

    public function __construct(ThreeDBettingWinRepositoryInterface $repo)
    {
        $this->bettingWinRepo = $repo;
    }

    public function list(Request $request)
    {
        $this->bettingWinRepo->listBettingWins($request);
    }

    public function create(Request $request)
    {
        if((!$request->number || !$request->twist_numbers) || ($request->number=='null' || $request->twist_numbers=='null')){
            ResponseMessage('Number and twist numbers must be present', 400);
        }

        if(!$this->isValidThreeDigitNumber($request->number)){
            ResponseMessage('Three D number is not correct', 400);
        }
        $twistNumbers = explode(',', $request->twist_numbers);
        foreach($twistNumbers as $twistNumber){
            if(!$this->isValidThreeDigitNumber($twistNumber)){
                ResponseMessage('Three D twist number is not correct', 400);
            }
        }

        $data = $request->except('twist_numbers');
        $data['date_time'] = CurrentTime();
        $data['created_by'] = ApiUser()->id;

        $bettingWin = $this->bettingWinRepo->createBettingWin($data, $twistNumbers);

        ResponseData($bettingWin);
    }

    public function update(Request $request, BettingWin $bettingWin)
    {
        if((!$request->number || !$request->twist_numbers) || ($request->number=='null' || $request->twist_numbers=='null')){
            ResponseMessage('Number and twist numbers must be present', 400);
        }

        if(!$this->isValidThreeDigitNumber($request->number)){
            ResponseMessage('Three D number is not correct', 400);
        }
        $twistNumbers = explode(',', $request->twist_numbers);
        foreach($twistNumbers as $twistNumber){
            if(!$this->isValidThreeDigitNumber($twistNumber)){
                ResponseMessage('Three D twist number is not correct', 400);
            }
        }

        $data = $request->except('twist_numbers');
        $data['date_time'] = CurrentTime();
        $data['created_by'] = ApiUser()->id;

        $bettingWin = $this->bettingWinRepo->updateBettingWin($data, $twistNumbers, $bettingWin);

        ResponseData($bettingWin);
    }

    public function approve(Request $request, BettingWin $bettingWin)
    {
        $this->bettingWinRepo->approveBettingWin($bettingWin);
    }

    private function isValidThreeDigitNumber($str) {
        return preg_match('/^\d{3}$/', $str) === 1;
    }
}
