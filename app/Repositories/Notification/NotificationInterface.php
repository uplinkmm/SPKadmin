<?php

namespace App\Repositories\Notification;

interface NotificationInterface
{
    public function list($reques);

    public function readNotificaiton($request);
}
