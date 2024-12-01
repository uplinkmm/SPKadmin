<?php

namespace App\Actions\TwoD;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

use GuzzleHttp\Client;

use App\Repositories\TwoDResult\TwoDResultRepository;

class FetchThaiStockTwoDAction
{
    private $twoDResultRepo;

    public function __construct()
    {
        $this->twoDResultRepo = app(TwoDResultRepository::class);
    }

    public function run()
    {
        $client = new Client(['base_uri' => 'https://api.thaistock2d.com/']);
        try {
            // Make a GET request
            $response = $client->get('live');

            // Process the API response (e.g., save orders)
            $responseData = json_decode($response->getBody(), true);
            $liveData = $responseData['live'];

            $results = $responseData['result'];
            foreach($results as $result){
                if($result['history_id'] == null){
                    continue;
                }
                if($this->twoDResultRepo->saveTwoDResult($result)){
                    Log::info('result saved');
                }
                else{
                    Log::error('result not saved');
                }
            }

            $holidayData = $responseData['holiday'];

            if($holidayData['name'] == 'NULL'){
                foreach($results as $i=>$result){
                    $results[$i]['day_part'] = (date_create($result['stock_datetime']))->format('a');
                }
                $liveData['results'] = $results;
                $data = json_encode($liveData);

                Storage::put('thaistock2d_live_data.json', $data);
            }
            else{

            }

            return true;
        } catch (\Exception $e) {
            // Handle exceptions (e.g., connection error)
            // echo "Error: " . $e->getMessage();
            Log::error($e->getMessage());
            return false;
        }
    }
}
