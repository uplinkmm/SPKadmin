<?php

namespace App\Repositories\LiveData;

use Illuminate\Http\Request;

interface TwoDResultRepositoryInterface
{
    public function saveTwoDResult(array $data);

    public function importTwoDResults(array $results);

    public function getTwoDResultDaily(Request $request);

    public function getTwoDResultMonthly(Request $request);

    public function getTwoDFrequencyAnalysis(Request $request);

    public function getTwoDLuckyNumbers();
}
