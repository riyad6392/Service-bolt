<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;

class PushNotificationService
{
    public function sendPushNotification($deviceToken, $title, $message, $topic)
    {

        $firebase = (new Factory)
            ->withServiceAccount(base_path('config/firebase_credentials.json'));

        
        $messaging = $firebase->createMessaging();

        $message = CloudMessage::fromArray([
            'notification' => [
                'title' => $title,
                'body' => $message
            ],
            'token' => $deviceToken,
//            'topic' => $topic
        ]);

        return $messaging->send($message);
        
    }
    
}