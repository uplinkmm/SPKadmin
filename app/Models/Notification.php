<?php

namespace App\Models;

use LaravelFCM\Facades\FCM;
use App\Models\NotificationPerson;
use LaravelFCM\Message\OptionsBuilder;
use Illuminate\Database\Eloquent\Model;
use LaravelFCM\Message\PayloadDataBuilder;
use Illuminate\Database\Eloquent\SoftDeletes;
use LaravelFCM\Message\PayloadNotificationBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notification extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable=['title','preview','notificationable_id','notificationable_type','createdable_id','createdable_type','date_time'];
    public function notificationPerson(){
        return $this->hasMany(\App\Models\NotificationPerson::class);
    }
    public function notificationable()
    {
        return $this->morphTo();
    }
}
