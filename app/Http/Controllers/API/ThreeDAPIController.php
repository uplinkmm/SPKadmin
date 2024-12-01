<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Repositories\ThreeDResult\ThreeDResultRepositoryInterface;

use Rap2hpoutre\FastExcel\FastExcel;

class ThreeDAPIController extends Controller
{
    private $threeDRepo;

    public function __construct(ThreeDResultRepositoryInterface $repo)
    {
        $this->threeDRepo = $repo;
    }

    public function saveThreeDResult(Request $request)
    {
        $data = $request->all();
        $result = $this->threeDRepo->saveThreeDResult($data);

        ResponseData($result);
    }

    public function getResults(Request $request)
    {
        $results = $this->threeDRepo->getThreeDResults($request);
        foreach($results as $result){
            $result->extra_winning_numbers = json_decode($result->extra_winning_numbers, true);
        }

        ResponseData($results);
    }

    public function getCalendarResults(Request $request)
    {
        $results = $this->threeDRepo->getCalendarResults($request);

        ResponseData($results);
    }

    public function getYearlyResults(Request $request)
    {
        $results = $this->threeDRepo->getYearlyResults($request);

        ResponseData($results);
    }

    public function getAnalysis(Request $request)
    {
        $result = $this->threeDRepo->getThreeDAnalysisResults($request);

        ResponseData($result);
    }

    public function importThreeDResults(Request $request)
    {
        if(!$request->hasFile('data_sheet')){
            ResponseMessage('No data sheet present', 400);
        }

        $excelFile = $request->file('data_sheet');
        $filePath = $excelFile->path();
        $sheets = (new FastExcel)->withSheetsNames()->importSheets($filePath);

        $results = $sheets['3D'];

        $success =  $this->threeDRepo->importThreeDResults($results);
        if($success){
            ResponseMessage('Historical 3D results imported successfully');
        }
        else{
            ResponseMessage('3D results import error');
        }
    }
}
