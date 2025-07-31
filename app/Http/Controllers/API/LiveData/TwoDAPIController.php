<?php

namespace App\Http\Controllers\API\LiveData;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Actions\LiveData\FetchHistoricalThaiStockTwoDAction;

use App\Repositories\LiveData\TwoDResultRepositoryInterface;

use Rap2hpoutre\FastExcel\FastExcel;

class TwoDAPIController extends Controller
{
    //
    private $twoDRepo;

    public function __construct(TwoDResultRepositoryInterface $repo)
    {
        $this->twoDRepo = $repo;
    }

    public function getLiveResult(Request $request)
    {
        $liveDataString = Storage::disk('local')->get('thaistock2d_live_data.json');
        $liveData = json_decode($liveDataString, true);
        $modernInternetData = json_decode(Storage::disk('local')->get('thaistock2d_modern_internet_data.json'));
        $liveData['modern_internet'] = $modernInternetData;

        // Filter out results with open_time == '11:00:00' or '15:00:00'
        if (isset($liveData['results']) && is_array($liveData['results'])) {
            $liveData['results'] = collect($liveData['results'])
                ->reject(fn ($item) => in_array($item['open_time'], ['11:00:00', '15:00:00']))
                ->values() // reindex the array
                ->all();   // convert back to plain array
        }
        ResponseData($liveData);
    }

    public function getDailyResults(Request $request)
    {
        $results = $this->twoDRepo->getTwoDResultDaily($request);

        ResponseData($results);
    }

    public function getMonthlyResults(Request $request)
    {
        $results = $this->twoDRepo->getTwoDResultMonthly($request);

        ResponseData($results);
    }

    public function getAnalysis(Request $request)
    {
        $results = $this->twoDRepo->getTwoDFrequencyAnalysis($request);

        ResponseData($results);
    }

    public function getLuckyNumbers(Request $request)
    {
        $results = $this->twoDRepo->getTwoDLuckyNumbers();

        ResponseData($results);
    }

    public function importTwoDResults(Request $request)
    {
        if(!$request->hasFile('data_sheet')){
            ResponseMessage('No data sheet present', 400);
        }

        $excelFile = $request->file('data_sheet');
        $filePath = $excelFile->path();
        $sheets = (new FastExcel)->withSheetsNames()->importSheets($filePath);

        $sheetNames = ['2014', '2015', '2016', '2017', '2018', '2019', '2020', '2021', '2022', '2023', '2024'];

        $success = false;

        foreach($sheetNames as $sheetName){
            $results = $sheets[$sheetName];
            $success = $this->twoDRepo->importTwoDResults($results);
            if(!$success){
                ResponseMessage('2D results import error');
            }
        }

        if($success){
            ResponseMessage('Historical 2D results imported successfully');
        }
        else{
            ResponseMessage('2D results import error');
        }
    }

    public function fetchHitoricalResults(Request $request)
    {
        if(!$request->date && (!$request->start_date && !$request->end_date)){
            ResponseMessage('Date value must be set', 400);
        }

        if($request->date){
            if((new FetchHistoricalThaiStockTwoDAction())->run($request->date)){
                ResponseMessage('Historical results fetched and saved successfully');
            }

            else{
                ResponseMessage('Unknown error occurred', 500);
            }
        }

        if($request->start_date && $request->end_date){
            if((new FetchHistoricalThaiStockTwoDAction())->runWithStartEndDates($request->start_date, $request->end_date)){
                ResponseMessage('Historical results between two dates fetched and saved successfully');
            }

            else{
                ResponseMessage('Unknown error occurred', 500);
            }
        }
    }

    public function fetchModernInternet(Request $request)
    {
        $data = json_decode(Storage::disk('local')->get('thaistock2d_modern_internet_seven_day_data.json'), true);
        ResponseData($data);
    }
}
