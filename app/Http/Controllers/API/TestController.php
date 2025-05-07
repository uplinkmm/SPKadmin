<?php

namespace App\Http\Controllers\API;

use App\Models\Customer;
use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use App\Http\Action\NotificationWin;
use App\Http\Action\Notifying;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Notification as NotificationQueue;

class TestController extends Controller
{
    public function testNoti(Request $request){
        // foreach (Customer::all() as $customer) {
        //     $data['title']='Notification..';
        //     $data['body']='Successfuly Notification';
        //     $data['date_time']=now();
        //     $customer->notify(new Notifying($data));
        // }
        $data['title'] = 'New Notification';
        $data['body'] = 'Hello';
        $data['date_time'] = now();
        NotificationQueue::send(Customer::all(), new Notifying($data));
        ResponseMessage('Notificaion Send.....', 200);
        ResponseMessage('Notificaion Send.....',200);
    }
}
