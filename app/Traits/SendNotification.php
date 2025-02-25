<?php

namespace App\Traits;

use App\Models\Notification;
use InvalidArgumentException;
use App\Http\Action\Notifying;
use App\Models\PersonFcmToken;
use App\Models\NotificationPerson;
use Illuminate\Support\Facades\Notification as LaravelNotification;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Notification as NotificationQueue; // Add this line


trait SendNotification
{

    public function send($model, $people, $data)
    {
        $morphMapName = RelationMorphName($model);
        // $personMapName = RelationMorphName($people);
        $people = $this->normalizeToCollection($people);
        $notification = Notification::firstOrCreate(
            [
                'notificationable_id' => $model->id,
                'notificationable_type' => $morphMapName,
                // 'createdable_id' => ApiUser()->id,
                'createdable_type' => 'user',
            ],
            [
                'title' => $data['title'],
                'preview' => $data['body'],
                'date_time' => now(),
                'createdable_id' => ApiUser()->id,
                'createdable_type' => 'user',
            ]
        );
        $notificationPersons = [];
        // dd($people);
        // Log::info('notification', $data);
        foreach ($people as $person) {
            $personMorphMapName = RelationMorphName($person);
            $notificationPersons[] = [
                'notification_id' => $notification->id,
                'personable_id' => $person->id,
                'personable_type' => $personMorphMapName,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            // $notificationData = [
            //     'notification_id' => $notification->id,
            //     'personable_id' => $person->id,
            //     'personable_type' => $personMorphMapName,
            //     'created_at' => now(),
            //     'updated_at' => now(),
            // ];
            // NotificationPerson::create($notificationData);
            Log::info('created notification person');
        }
        NotificationPerson::insert($notificationPersons);
        // Batch send notifications (uses ShouldQueue)
        Log::info('Reach Notifcation Queue');
        NotificationQueue::send($people, new Notifying($data));
        // LaravelNotification::send($people, new Notifying($data));
        // dispatch(new Notifying($data));
        Log::info('Complete Notifcation Queue');
        // Log::info('notification', $data);
        // return $notificationPersons;
        // foreach($people as $person){
        //     $notificationPeopleData[] = [
        //         'notification_id' => $notification->id,
        //         'personable_id' => $person->id,
        //         'personable_type' => 'user',
        //     ];

        //     $person->notify(new Notifying($data));
        // }
        // NotificationPerson::insert($notificationPeopleData);

    }
    protected function normalizeToCollection($people)
    {
        // If $people is not already a collection, convert it to one
        if ($people instanceof Model) {
            return collect([$people]);
        } elseif (is_array($people)) {
            return collect($people);
        } elseif ($people instanceof Collection) {
            return $people;
        } else {
            throw new InvalidArgumentException('People should be a Model, array, or Collection.');
        }
    }

    public function getTokensByPerson($person_ids, $type)
    {
        return PersonFcmToken::whereIn('personable_id', $person_ids)
            ->where('personable_type', $type)
            ->pluck('fcm_token')->toArray();
    }
}
