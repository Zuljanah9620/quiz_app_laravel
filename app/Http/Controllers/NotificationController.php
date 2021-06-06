<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\AppConfig;

class NotificationController extends Controller
{

	public function __construct()
    {
        $this->middleware(['auth', 'user']);
    }

    public function index()
    {
        return view('notification');
    }

    public function send(Request $request) {
    	$out = new \Symfony\Component\Console\Output\ConsoleOutput();
		$out->writeln("Hello from Terminalaaaaaaa");
    	$rules = array(
            'notification_title'     =>  'required',
            'notification_body'     =>  'required',
            'notification_icon' =>  'required',
            'notification_image' =>  'nullable'
        );
        $error = Validator::make($request->all(), $rules);
        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $url = "https://fcm.googleapis.com/fcm/send";

        $postdata = 
            [
                'notification' => 
                [
                    'title' => $request->notification_title,
                    'body' => $request->notification_body,
                    'icon' => $request->notification_icon,
                    'tag'  => $request->notification_icon
                ]
                ,
                'to' => '/topics/all',
                'priority' => 'high'
            ];
        if(!is_null($request->notification_image)) {
        	$postdata['notification']['image'] = $request->notification_image;
        }

        $postdata = json_encode($postdata);


        $firebase_server_key = AppConfig::first();
        $firebase_server_key = $firebase_server_key->firebase_server_key;
        if(is_null($firebase_server_key)) {
        	return response()->json(['error' => 'Something went wrong']);
        }

        $opts = array('http' => 
            array(
                'method'  => 'POST',
                'header'  => 'Content-type: application/json'."\r\n"
                .'Authorization: key='.$firebase_server_key."\r\n",
                'content' => $postdata
            )
        );
        $context  = stream_context_create($opts);
        $result = file_get_contents($url, false, $context);

        if($result) {
            $result = json_decode($result);
            if(isset($result->message_id)) {
                return response()->json(['success' => 'Notification has been sent']);
            }
            else {
            	return response()->json(['error' => 'Something went wrong. Please check your Firebase Server Key']);  
            }        
        } 
        else {
        	return response()->json(['error' => 'Something went wrong']);
        }
    }
}
