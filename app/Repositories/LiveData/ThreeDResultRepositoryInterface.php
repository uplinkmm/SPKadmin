<?php

namespace App\Repositories\LiveData;

use Illuminate\Http\Request;

interface ThreeDResultRepositoryInterface
{
    public function saveThreeDResult(array $data);

    public function getThreeDResults(Request $request);

    public function getCalendarResults(Request $request);

    public function getYearlyResults(Request $request);

    public function getThreeDAnalysisResults(Request $request);

    public function importThreeDResults(array $results);
}
