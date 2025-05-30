<?php

namespace App\Repositories\LiveData;

use Exception;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\TwoDResult;

use App\Repositories\LiveData\TwoDResultRepositoryInterface;

class TwoDResultRepository implements TwoDResultRepositoryInterface
{
    public function saveTwoDResult(array $data)
    {
        $resultData = [
            'set' => $data['set'],
            'value' => $data['value'],
            'open_time' => $data['open_time'],
            'day_part' => (date_create($data['stock_datetime']))->format('a'),
            'twod' => $data['twod'],
            'stock_date' => $data['stock_date'],
            'stock_datetime' => $data['stock_datetime'],
            'history_id' => (int) $data['history_id']
        ];

        // dd($resultData);
        $success = false;
        DB::beginTransaction();
        try{
            $result = TwoDResult::updateOrCreate(
                ['history_id' => $resultData['history_id']],
                $resultData
            );
            DB::commit();
            $success = true;
        }catch(Exception $e){
            DB::rollBack();

            ResponseMessage($e->getMessage(), 500);
        }


        return $success;
    }

    public function importTwoDResults(array $results)
    {
        $success = false;
        DB::beginTransaction();
        try{
            foreach($results as $result){
                if(!$result['TwoD'])
                    continue;

                $date = ($result['Date'])->format('Y-m-d');
                $time = ($result['Open Time'])->format('H:i:s');
                $dayPart = ($result['Open Time'])->format('a');

                $historyIdPostFix = 1;
                if($time == '12:01:00'){
                    $historyIdPostFix = 1;
                }
                else{
                    $historyIdPostFix = 2;
                }
                $historyId = ($result['Date'])->format('ymd') . $historyIdPostFix;
                $historyId = (int) $historyId;
                $data = [
                    'history_id' => $historyId,
                    'stock_datetime' => $date . ' ' .  $time,
                    'stock_date' => $date,
                    'open_time' => $time,
                    'day_part' => $dayPart,
                    'twod' => $result['TwoD']
                ];

                TwoDResult::create($data);

            }

            DB::commit();
            $success = true;
        }
        catch(Exception $e){
            DB::rollBack();
            ResponseMessage($e->getMessage(), 400);
        }

        return $success;
    }

    public function getTwoDResultDaily(Request $request)
    {
        $date = CurrentDate();
        if($request->date){
            $date = $request->date;
        }

        $results = TwoDResult::where('stock_date', $date)->get();

        return $results;
    }

    public function getTwoDResultMonthly(Request $request)
    {
        $month = $request->month;
        $year = $request->year;
        $start_date = $year . '-' . $month . '-' . '01';
        $end_date = $year . '-' . $month . '-' . '31';

        $results = TwoDResult::where('stock_date', '>=', $start_date)
        ->where('stock_date', '<=', $end_date)
        ->where(function($query){
            $query->where('open_time', '12:01:00')
            ->orWhere('open_time', '15:00:00')
            ->orWhere('open_time', '16:30:00');
        })
        // ->whereIn('open_time', ['12:01:00', '15:00:00', '16:30:00'])
        // ->orderByRaw("FIELD(open_time, '12:01:00', '15:00:00', '16:30:00')")
        ->select(['stock_datetime','stock_date','open_time', 'day_part', 'twod'])
        ->orderBy('stock_date', 'asc')
        ->orderByRaw("FIELD(open_time, '12:01:00', '15:00:00', '16:30:00')")
        ->get();

        foreach($results as $i=>$result){
            if($results[$i]->open_time == '16:30:00'){
                // $results->forget($i - 1);
                // ResponseData($results[$i-1]);
                // continue;
            }
            $result->start = $result->stock_datetime;
            $result->title = $result->twod;
        }
        ResponseData($results);
        return $results;
    }

    public function getTwoDFrequencyAnalysis(Request $request)
    {
        $startDate = now()->subDays(7)->format('Y-m-d');
        $endDate = now()->format('Y-m-d');

        if($request->range){
            if($request->range == '1w'){
                $startDate = now()->subDays(7)->format('Y-m-d');
            }

            if($request->range == '1m'){
                $startDate = now()->subDays(30)->format('Y-m-d');
            }

            if($request->range == '2m'){
                $startDate = now()->subDays(30 * 2)->startOfMonth()->format('Y-m-d');
            }

            if($request->range == '6m'){
                $startDate = now()->subDays(30 * 6)->startOfMonth()->format('Y-m-d');
            }
        }

        $results = TwoDResult::whereBetween('stock_date', [$startDate, $endDate])
        ->where(function($query){
            $query->where('open_time', '12:01:00')->orWhere('open_time', '16:30:00');
        })
        ->select(['id','stock_date','open_time', 'day_part', 'twod'])
        ->orderBy('stock_date', 'asc')
        ->orderBy('open_time', 'asc')
        ->get();

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
            $result->day = date_create($result->stock_date)->format('D');
            $result->open_time = date('h:i A', strtotime($result->open_time));
            $digits = str_split($result->twod);
            $result->twod_numbers = $digits;
            foreach($digits as $digit){
                $frequencies[$digit] += 1;
                unset($targetMissingNumbers[ (int) $digit]);
            }
        }

        foreach($targetMissingNumbers as $missingNumber){
            array_push($missingNumbers, $missingNumber);
        }

        $results = $results->groupBy('stock_date')->values();

        return ['results' => $results, 'frequencies' => $frequencies, 'missing_numbers' => $missingNumbers];
    }

    public function getTwoDLuckyNumbers()
    {
        $randomNumbers = $this->generateNonOverlappingRandomNumbers(0,9,4);
        $permutatedNumbers = $this->generatePermutations($randomNumbers);

        $numbersWithFrequency = [];

        foreach($permutatedNumbers as $permutatedNumber){
            $frequency = TwoDResult::where('twod', $permutatedNumber)->count();
            $lastDate = TwoDResult::where('twod', $permutatedNumber)->select(['id', 'history_id', 'stock_datetime', 'day_part','twod'])->get()->last();
            array_push($numbersWithFrequency, ['twoD' => $permutatedNumber, 'frequency' => $frequency, 'last_occurrence'=>$lastDate]);
        }

        return ['lucky_numbers' => $randomNumbers, 'combinations' => $numbersWithFrequency];
    }

    private function generateNonOverlappingRandomNumbers($min, $max, $count) {
        $numbers = [];

        while (count($numbers) < $count) {
            $randomNumber = rand($min, $max);

            // Check if the generated number is not already in the array
            if (!in_array($randomNumber, $numbers)) {
                $numbers[] = $randomNumber;
            }
        }

        return $numbers;
    }

    private function generatePermutations($numbers) {
        $permutations = [];

        // Iterate through each pair of numbers in the array
        for ($i = 0; $i < count($numbers); $i++) {
            for ($j = 0; $j < count($numbers); $j++) {
                // Skip if both indices are the same
                if ($i == $j) {
                    continue;
                }

                // Concatenate the two numbers and add to permutations array
                $permutations[] = $numbers[$i] . $numbers[$j];
            }
        }

        return $permutations;
    }
}
