<?php

namespace App\Services;

use Exception;

use GuzzleHttp\Client;
use Google\Client as Google_Client;

class FirebaseService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function sendNotification($title, $body, $token)
    {
        try{
            $response = $this->client->post("https://fcm.googleapis.com/v1/projects/twodmmpro/messages:send", [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->getAccessToken(),
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'message' => [
                        'token' => $token,
                        'notification' => [
                            'title' => $title,
                            'body' => $body,
                        ],
                    ],
                ],
            ]);

            ResponseMessage('FCM message sent to device successfully');
        }
        catch(Exception $e){
            ResponseMessage($e->getMessage());
        }

    }

    public function sendNotificationToMultipleDevices($title, $body, array $tokens)
    {
        try{
            foreach($tokens as $token){
                $response = $this->client->post("https://fcm.googleapis.com/v1/projects/twodmmpro/messages:send", [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $this->getAccessToken(),
                        'Content-Type' => 'application/json',
                    ],
                    'json' => [
                        'message' => [
                            'token' => $token,
                            'notification' => [
                                'title' => $title,
                                'body' => $body,
                            ],
                        ],
                    ],
                ]);
            }

            ResponseMessage('FCM message sent to multiple devices successfully');
        }
        catch(Exception $e){
            ResponseMessage($e->getMessage());
        }
    }

    protected function getAccessToken()
    {
        $client = new Google_Client();
        $client->setAuthConfig(storage_path('app/service-account.json'));
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');

        return $client->fetchAccessTokenWithAssertion()['access_token'];
    }
}
