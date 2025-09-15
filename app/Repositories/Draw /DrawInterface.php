<?php

namespace App\Repositories\Draw;

use Illuminate\Http\Request;

interface DrawInterface
{
    public function list($request);
    public function create($request);
    public function detail($id);

    public function toggleIsActive($request);

    public function lotteryPromotionList($request);
    public function lotteryPromotionCreate($request);
    public function lotteryPromotiondetail($id);

    //lottery winnig number
    public function lotteryWinningNumberList($request);
    public function lotteryWinningNumberCreate($request);
    public function lotteryWinningNumberdetail($id);
    public function approveLotteryWinnigNumber($request);
    public function editLotteryWinnigNumber($request);
    public function lotteryWinningUserList($request);
    // end
    public function prizeByGame($gameSettingId);

    public function lotteryBettingList($request);

}
