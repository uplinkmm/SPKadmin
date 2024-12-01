<?php

namespace App\Repositories\ThreeDResult;

use Exception;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\ThreeDResult;

use App\Repositories\ThreeDResult\ThreeDResultRepositoryInterface;
use Error;

class ThreeDResultRepository implements ThreeDResultRepositoryInterface
{
    public function saveThreeDResult(array $data)
    {
        $extraWinningNumbers = null;
        if(isset($data['extra_winning_numbers'])){
            $extraWinningNumbers = json_encode($data['extra_winning_numbers']);
        }
        $resultData = [
            'open_datetime' => CurrentTime(),
            'open_date' => CurrentDate(),
            'winning_number' => $data['winning_number'],
            'year' => now()->format('Y'),
            'extra_winning_numbers' => $extraWinningNumbers
        ];

        $threeDResult = ThreeDResult::create($resultData);

        return $threeDResult;
    }

    public function getThreeDResults(Request $request)
    {
        $threeDResults = ThreeDResult::all();

        return $threeDResults;
    }

    public function getCalendarResults(Request $request)
    {
        $results = ThreeDResult::all();
        foreach($results as $result){
            $result->month = date_create($result->open_date)->format('m');
        }
        $results = $results->groupBy('year')->values();

        return $results;
    }

    public function getYearlyResults(Request $request)
    {
        $year = now()->format('Y');
        if($request->year){
            $year = $request->year;
        }

        $results = ThreeDResult::where('year', $year)->get();

        return $results;
    }

    public function getThreeDAnalysisResults(Request $request)
    {
        $year = now()->format('Y');
        if($request->year){
            $year = $request->year;
        }

        $results = ThreeDResult::where('year', $year)->get();
        $frequencies = [
            '0' => 0,
            '1' => 0,
            '2' => 0,
            '3' => 0,
            '4' => 0,
            '5' => 0,
            '6' => 0,
            '7' => 0,
            '8' => 0,
            '9' => 0
        ];
        $targetMissingNumbers = [0,1,2,3,4,5,6,7,8,9];
        $missingNumbers = [];

        foreach($results as $result){
            $digits = str_split($result->winning_number);
            $result->threed_numbers = $digits;
            foreach($digits as $digit){
                $frequencies[$digit] += 1;
                unset($targetMissingNumbers[ (int) $digit]);
            }
        }

        foreach($targetMissingNumbers as $missingNumber){
            array_push($missingNumbers, $missingNumber);
        }

        return ['results' => $results, 'frequencies' => $frequencies, 'missing_numbers' => $missingNumbers];
    }

    public function importThreeDResults(array $results)
    {
        try{
            DB::beginTransaction();
            foreach($results as $result){
                if(!$result['Winner Number'])
                    continue;

                try{
                    $date = ($result['Open Date'])->format('Y-m-d');
                    $time = ($result['Open Date'])->format('Y-m-d H:i:s');
                    $year = ($result['Open Date'])->format('Y');
                    $data = [
                        'open_datetime' => $time,
                        'open_date' => $date,
                        'year' => $year,
                        'winning_number' => $result['Winner Number'],
                        'extra_winning_numbers' => $result['Extra Winning Number']
                    ];
                    ThreeDResult::create($data);
                }catch(Error $error){
                    ResponseMessage($error->getMessage(), 500);
                }

            }
            DB::commit();
            return true;
        }catch(Exception $e){
            DB::rollBack();
            return false;
        }

    }
}
