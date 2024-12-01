<?php

namespace App\Actions\TwoD;

use Exception;

use Illuminate\Support\Facades\Storage;

use App\Actions\TwoD\FetchModernInternetTwoDAction;

class SaveModernInternetTwoDAction
{
    public function run()
    {
        $data = (new FetchModernInternetTwoDAction())->run();
        $existingData = null;
        try{
            $existingData = json_decode(Storage::disk('local')->get('thaistock2d_modern_internet_seven_day_data.json'), true);
        }catch(Exception $e){

        }
        if($existingData){
            $records = $existingData;
            array_push($records, $data);
            if(count($records) > 7){
                $records = array_slice($records, -7, 7, true);
            }
            $records = array_values($records);
            Storage::put('thaistock2d_modern_internet_seven_day_data.json', json_encode($records));
        }else{
            $records = [$data];
            Storage::put('thaistock2d_modern_internet_seven_day_data.json', json_encode($records));
        }
    }
}
