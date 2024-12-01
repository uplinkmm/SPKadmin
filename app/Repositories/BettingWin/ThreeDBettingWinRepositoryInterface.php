<?php

namespace App\Repositories\BettingWin;

use Illuminate\Http\Request;

use App\Models\BettingWin;

interface ThreeDBettingWinRepositoryInterface
{
    public function listBettingWins(Request $request);

    public function createBettingWin(array $data, array $twistNumbers);

    public function updateBettingWin(array $data, array $twistNumbers, BettingWin $bettingWin);

    public function approveBettingWin(BettingWin $bettingWin);
}
