<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
                                            
use App\Http\Controllers\Controller;

use App\Repositories\DreamNumber\DreamNumberRepositoryInterface;

use Rap2hpoutre\FastExcel\FastExcel;

class DreamNumberAPIController extends Controller
{
    //
    private $dreamNumberRepo;

    public function __construct(DreamNumberRepositoryInterface $repo)
    {
        $this->dreamNumberRepo = $repo;
    }

    public function importDreamNumberSheet(Request $request)
    {
        if(!$request->hasFile('data_sheet')){
            ResponseMessage('No data sheet present', 400);
        }

        $dreamNumbers = [];
        $excelFile = $request->file('data_sheet');
        $filePath = $excelFile->path();
        $rows = (new FastExcel())->import($filePath);
        foreach($rows as $row){
            array_push($dreamNumbers, $row);
        }
        $success = $this->dreamNumberRepo->bulkCreate($dreamNumbers);

        if($success){
            ResponseMessage('Dream numbers imported');
        }
        else{
            ResponseMessage('Dream numbers import error', 400);
        }
    }

    public function getDreamNumbers(Request $request)
    {
        $dreamNumbers = $this->dreamNumberRepo->getDreamNumbers($request);

        ResponseData($dreamNumbers);
    }
}
