<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\LotteryWinningNumber;
use App\Repositories\Draw\DrawInterface;

class LotteryWinningNumberController extends Controller
{
    //
    private $drawRepo;
    public function __construct(DrawInterface $repo){
        $this->drawRepo=$repo;
    }

    public function index(Request $request){
        $data=$this->drawRepo->lotteryWinningNumberList($request);
        ResponseData($data);
    }

    public function store(Request $request){
        $data=$this->drawRepo->lotteryWinningNumberCreate($request);
        ResponseData($data);
    }

    public function show(LotteryWinningNumber $lottery_winning_number){
        $data=$this->drawRepo->lotteryWinningNumberdetail($lottery_winning_number);
        ResponseData($data);
    }

    public function approveLotteryWinnigNumber(Request $request){
        $data=$this->drawRepo->approveLotteryWinnigNumber($request);
        ResponseData($data);
    }

    public function editLotteryWinnigNumber(Request $request){
        $data=$this->drawRepo->editLotteryWinnigNumber($request);
        ResponseData($data);
    }

    public function lotteryWinningUserList(Request $request){
        $data=$this->drawRepo->lotteryWinningUserList($request);
        ResponseData($data);
    }
}
