$user = User::where("id", $cartdata->user_id)->first();

$msgarray = array(
    'title' => 'Order Status',
    'msg' => $notification->description,
    'type' => 'OrderAssign',
);
$fcmData = array(
    'message' => $msgarray['msg'],
    'body' => $msgarray['title'],
);
$this->sendFirebaseNotification($user, $msgarray, $fcmData);

=====================

public function sendFirebaseNotification($user, $msgarray, $fcmData) {
    $url = 'https://fcm.googleapis.com/fcm/send';

    $fcmApiKey = "AAAAFh3Qleo:APA91bE9OgFnFhdZ9Vy7bOr2rNFtVt9HJ4oZ1wz4LY66dSEjgV7xftPtAVu96Z84Jn-o50bj2Lbe_bikEs7EN9zOt-eVggngG6hic4eOZUONUy8XbChTa_mr3kCr_hNHCgaVjS2lIq6W
";
    
    $fcmMsg = array(
        'title' => $msgarray['title'],
        'text' => $msgarray['msg'],
        'type' => $msgarray['type'],
        'vibrate' => 1,
        "date_time" => date("Y-m-d H:i:s"),
        'message' => $msgarray['msg'],
    );
    
    if ($user->device_type == "ios") {
        $fcmFields = array(
            'to' => $user->device_token,
            'priority' => 'high',
            'notification' => $fcmMsg,
            'data' => $fcmMsg,
        );
    } else {
        $fcmFields = array(
            'to' => $user->device_token,
            'priority' => 'high',
            'data' => $fcmMsg,
        );
    }
    
    $headers = array(
        'Authorization: key=' . $fcmApiKey,
        'Content-Type: application/json',
    );
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmFields));
    $result = curl_exec($ch);
    
    if ($result === false) {
        // die('Curl failed: ' . curl_error($ch));
    }
    curl_close($ch);
    // echo "\n##################################################################################################";
    // echo "\n\n\n";
    return $result;
}