<?php
namespace App\Http\Action;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use NotificationChannels\Fcm\Resources\Notification as FcmNotification;

class Notifying extends Notification implements ShouldQueue
{
    use Queueable;
    private $title;
    private $body;
    private $date_time;

    public function __construct(array $data)
    {
        $this->title = $data['title'];
        $this->body = $data['body'];
        $this->date_time = $data['date_time'];
    }
    public function via($notifiable)
    {
        Log::info('Notifying via FCM:', ['notifiable' => $notifiable]);
        return [FcmChannel::class];
    }
    // public function toFcm($notifiable)
    // {
    //     return FcmMessage::create()
    //         ->setData([
    //             'title' => 'Congratulations!',
    //             'body' => 'You have won the betting game!',
    //         ]);
    // }

    public function toFcm($notifiable): FcmMessage
    {
        $tokens = $notifiable->routeNotificationFor('fcm');
        if (empty($tokens)) {
            Log::error('No FCM token found for notifiable:', ['notifiable' => $notifiable]);
            // return;
        }
    
        Log::info('Sending FCM Notification', [
            'tokens' => $tokens, // Log all tokens
            'title' => $this->title,
            'body' => $this->body
        ]);

        return (new FcmMessage(notification: new FcmNotification(
            title: $this->title,
            body: $this->body,
            image: 'http://example.com/url-to-image-here.png'
        )))
        ->data(['data1' => 'value', 'data2' => 'value2'])
        ->custom([
            'android' => [
                'notification' => [
                    'color' => '#0A0A0A',
                ],
                'fcm_options' => [
                    'analytics_label' => 'analytics',
                ],
            ],
            'apns' => [
                'fcm_options' => [
                    'analytics_label' => 'analytics',
                ],
            ],
        ]);
        return (new FcmMessage(notification: new FcmNotification(
                title: $this->title,
                body: $this->body,
                image: 'http://example.com/url-to-image-here.png'
            )))
            ->data(['data1' => 'value', 'data2' => 'value2'])
            ->custom([
                'android' => [
                    'notification' => [
                        'color' => '#0A0A0A',
                    ],
                    'fcm_options' => [
                        'analytics_label' => 'analytics',
                    ],
                ],
                'apns' => [
                    'fcm_options' => [
                        'analytics_label' => 'analytics',
                    ],
                ],
            ]);
    }
}