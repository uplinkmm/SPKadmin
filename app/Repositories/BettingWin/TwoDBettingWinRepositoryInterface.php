<?php

namespace App\Repositories\BettingWin;

use Illuminate\Http\Request;

use App\Models\BettingWin;

interface TwoDBettingWinRepositoryInterface
{
    public function listBettingWins(Request $request);

    public function createBettingWin(array $data);

    public function upateBettingWin(array $data, BettingWin $bettingWin);

    public function approveBettingWin(BettingWin $bettingWin);
}
