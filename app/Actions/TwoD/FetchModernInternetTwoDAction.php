<?php

namespace App\Actions\TwoD;

use Exception;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

use GuzzleHttp\Client;

class FetchModernInternetTwoDAction
{
    public function run()
    {
        $client = new Client(['base_uri' => 'https://luke.2dboss.com/']);

        try{
            $response = $client->get('api/luke/twod-result-live');
            $responseData = json_decode($response->getBody(), true);
            $data = $responseData['data'];
            $nineThirtyInternet = $data['internet_930'];
            $nineThirtyModern = $data['modern_930'];
            $fourteenInternet = $data['internet_200'];
            $fourteenModern = $data['modern_200'];

            $data = [
                "date" => CurrentDate(),
                "numbers" => [
                    [
                        "time" => "9:30 AM",
                        "Modern" => $nineThirtyModern,
                        "Internet" => $nineThirtyInternet
                    ],
                    [
                        "time" => "2:00 PM",
                        "Modern" => $fourteenModern,
                        "Internet" => $fourteenInternet
                    ]
                ]
            ];

            Storage::put("thaistock2d_modern_internet_data.json", json_encode($data));

            return $data;
        }catch(Exception $e){
            LOg::error($e->getMessage());
            return false;
        }
    }
}
