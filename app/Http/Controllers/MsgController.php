<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\FCM\Push;
use App\FCM\Firebase;
use Illuminate\Http\Request;

class MsgController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
        $firebase = new Firebase();
        $token = $firebase->createToken(1,1);
        echo "<pre>";
        print_r($token);
        echo "</pre>";
        exit;
        EXIT;
       
        // Preparing message content
        $push = new Push();
        // optional payload
        $payload = [
            'team' => 'VNN',
            'score' => '5.6'
        ];
        $push->setTitle("TITLE");
        $push->setMessage("content content");
        $push->setPayload($payload);
        $messageContent = $push->getPush();
        
        // lay trong user setting
        $fcm_id = 'cHPE3F8Vb28:APA91bFnwHVC6dB1LbLM3T0SO7MD6f8ws6H8TS56GRBki7u5vbmxXLhz4mgE3Rmb14SqVp6x9pKn44sL6XOthdRiV5aU8iAaCPYIVDfj4RUibL_BPPE67HEPv1XWkJY0ifSsEr-OUOri';
        
        $firebase = new Firebase();
        $response = $firebase->send($fcm_id, $messageContent);
        
        echo "<pre>";
        print_r($response);
        echo "</pre>";
        
        // sau khi send xog thì luu UserReceiveMessageModel message id vừa insert db va user id của fcm_id
        
        exit;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		
        return view('home');
    }
}
