<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;

class PushNotificationService
{
    public function sendPushNotification($deviceToken, $message)
    {
        $firebase = (new Factory)
            ->withServiceAccount(base_path('config/firebase_credentials.json'));
        
        $messaging = $firebase->createMessaging();
        
        $message = CloudMessage::fromArray([
            'notification' => [
                'title' => 'Hello from Firebase!',
                'body' => $message
            ],
            'token' => $deviceToken,
        ]);
        return $messaging->send($message);
        
    }
    
}