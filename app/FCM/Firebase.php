<?php
namespace App\FCM;
use Firebase\JWT\JWT;

class Firebase
{

    // sending push message to single user by firebase reg id
    public function send($to, $message)
    {
        $fields = array(
            'to' => $to,
            'data' => $message,
        );
        return $this->sendPushNotification($fields);
    }

    // Sending message to a topic by topic name
    public function sendToTopic($to, $message)
    {
        $fields = array(
            'to' => '/topics/' . $to,
            'data' => $message,
        );
        return $this->sendPushNotification($fields);
    }

    // sending push message to multiple users by firebase registration ids
    public function sendMultiple($registration_ids, $message)
    {
        $fields = array(
            'to' => $registration_ids,
            'data' => $message,
        );

        return $this->sendPushNotification($fields);
    }

    // function makes curl request to firebase servers
    private function sendPushNotification($fields)
    {
        // Set POST variables
        $url = env('CM_API', '');

        $headers = array(
            'Authorization: key=' . env('CM_KEY', ''),
            'Content-Type: application/json'
        );
        // Open connection
        $ch = curl_init();

        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        // Execute post
        $result = curl_exec($ch);
        if ($result === false) {
            // die('Curl failed: ' . curl_error($ch));
        }

        // Close connection
        curl_close($ch);

        return $result;
    }
    
    public function createToken($uid, $is_premium_account) {
        $private_key = env('CM_PRIVATE_KEY', '');
        $now_seconds = time();
        $payload = array(
            "iss" => env('EMAIL_SERVICE', ''),
            "sub" => env('EMAIL_SERVICE', ''),
            "aud" => "https://identitytoolkit.googleapis.com/google.identity.identitytoolkit.v1.IdentityToolkit",
            "iat" => $now_seconds,
            "exp" => $now_seconds+(60*60),  // Maximum expiration time is one hour
            "uid" => $uid,
            "claims" => array(
                "premium_account" => $is_premium_account
            )
        );
        return JWT::encode($payload, $private_key, "RS256");
    }
    
    public function sendMessage($fields) {
        // Set POST variables
        $url = env('CM_API', '');

        $headers = array(
            'Authorization: key=' . env('CM_KEY', ''),
            'Content-Type: application/json'
        );
        // Open connection
        $ch = curl_init();

        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        // Execute post
        $result = curl_exec($ch);
        if ($result === false) {
            // die('Curl failed: ' . curl_error($ch));
        }

        // Close connection
        curl_close($ch);

        return $result;
    }
}
