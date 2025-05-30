<?php

namespace App\Actions\LiveData;

use DateTime;
use DateInterval;
use DatePeriod;

use Illuminate\Support\Facades\Log;

use GuzzleHttp\Client;

use App\Repositories\LiveData\TwoDResultRepository;

class FetchHistoricalThaiStockTwoDAction
{
    private $twoDResultRepo;

    public function __construct()
    {
        $this->twoDResultRepo = app(TwoDResultRepository::class);
    }

    public function run($date)
    {
        $client = new Client(['base_uri' => 'https://api.thaistock2d.com/']);
        try{
            $response = $client->get('2d_result?date=' . $date);
            $responseData = json_decode($response->getBody(), true);
            if(count($responseData)<1){
                return false;
            }
            $historicalResults = $responseData[0]['child'];
            foreach($historicalResults as $historicalResult){
                $historicalResult['open_time'] = $historicalResult['time'];
                $historicalResult['stock_date'] = $date;
                $historicalResult['stock_datetime'] = $date . ' ' . $historicalResult['time'];
                if($this->twoDResultRepo->saveTwoDResult($historicalResult)){
                    Log::info('historical result saved');
                }
                else{
                    Log::error('historical result not saved');
                }
            }

            return true;
        }catch (\Exception $e) {
            // Handle exceptions (e.g., connection error)
            // echo "Error: " . $e->getMessage();
            Log::error($e->getMessage());
            return false;
        }
    }

    public function runWithStartEndDates($startDate, $endDate)
    {
        $dates = $this->getDatesInRange($startDate, $endDate);
        // dd($dates);
        $client = new Client(['base_uri' => 'https://api.thaistock2d.com/']);
        foreach($dates as $date){
            try{
                $response = $client->get('2d_result?date=' . $date);
                $responseData = json_decode($response->getBody(), true);
                if(count($responseData)<1){
                    Log::alert("responseData count less than 1 at {$date}");
                    // return false;
                    continue;
                }
                $historicalResults = $responseData[0]['child'];
                foreach($historicalResults as $historicalResult){
                    $historicalResult['open_time'] = $historicalResult['time'];
                    $historicalResult['stock_date'] = $date;
                    $historicalResult['stock_datetime'] = $date . ' ' . $historicalResult['time'];
                    if($this->twoDResultRepo->saveTwoDResult($historicalResult)){
                        Log::info("'historical result saved for {$date}'");
                    }
                    else{
                        Log::error("'historical result not saved' for {$date}");
                    }
                }
            }catch (\Exception $e) {
                // Handle exceptions (e.g., connection error)
                // echo "Error: " . $e->getMessage();
                Log::error($e->getMessage());
                return false;
            }
        }

        return true;
    }

    private function getDatesInRange($startDate, $endDate)
    {
        // Create DateTime objects from the provided start and end dates
        $start = new DateTime($startDate);
        $end = new DateTime($endDate);

        // Add one day to the end date to include the last day in the range
        $end->modify('+1 day');

        // Define the interval (1 day)
        $interval = new DateInterval('P1D');

        // Create a DatePeriod object with the start date, interval, and end date
        $datePeriod = new DatePeriod($start, $interval, $end);

        // Initialize an array to hold the dates
        $dates = [];

        // Iterate over the DatePeriod and format each date
        foreach ($datePeriod as $date) {
            $dates[] = $date->format('Y-m-d');
        }

        return $dates;
    }
}
