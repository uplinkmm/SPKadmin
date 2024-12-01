<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\Notification\NotificationInterface;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    //
    private $notificationRepo;
    public function __construct(NotificationInterface $repo)
    {
        $this->notificationRepo=$repo;
    }

    public function index(Request $request){
        $data=$this->notificationRepo->list($request);
        ResponseData($data);
    }

    public function readNotification(Request $request){
        $data=$this->notificationRepo->readNotificaiton($request);
        ResponseData($data);
    }
   
}
