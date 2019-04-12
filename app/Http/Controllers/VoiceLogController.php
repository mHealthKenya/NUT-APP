<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use AfricasTalking\SDK\AfricasTalking;
use App\VoiceLog;
use App\Client;
use App\EducationalMsg;
use App\OutgoingMsg;
use Redirect;
use App\User;
use Illuminate\Support\Facades\Auth;

class VoiceLogController extends Controller
{
    //
   
    public function send($to, $message){
       // $to=$request->input('to');
       // $message =$request->input('message');

        $username = "mhealthkenya";
        $apiKey = "9318d173cb9841f09c73bdd117b3c7ce3e6d1fd559d3ca5f547ff2608b6f3212";
        $AT       = new AfricasTalking($username, $apiKey);

        // Get one of the services
        $sms      = $AT->sms();
        // Use the service
        $send   = $sms->send([
                        'from' => '40149',
                        'to'      => $to,
                        'message' => $message
                    ]);



            return $send['status'] ;

        }

    

    
    public function sendVoice(){
        //app credentials
        $username = "mhealthkenya";
        $apiKey = "9318d173cb9841f09c73bdd117b3c7ce3e6d1fd559d3ca5f547ff2608b6f3212";
        //initialize the SDK
        $AT       = new AfricasTalking($username, $apiKey);

        //get the voice service 
        $voice    = $AT->voice();

        // Set your Africa's Talking phone number in international format
        $from     = "+254711082608";

        //set number you want to call, comma separated list if more than one
        $to = "+254705255873";

        try {
        //    Make the call
            $send = $voice->call([
                'from' => $from,
                'to'   => $to
            ]);
            // $results = $voice->call($from, $to);
           print_r($send);

        //loop through the numbers if more than one
        // foreach ($results as $result) {
        //     # code...
        //     echo $result->status;
        //     echo $result->phoneNumber;
        //     echo "<br/>";
        // } 
        } catch (Exception $e) {
            echo "Error: ".$e->getMessage();
        }

        if($send){
            $voice = new VoiceLog;
            $voice->phoneNumber = $to;

        }

    }
    public function getDigits(){
        $fileUrl = "http://www.amazon.co.us/mypromptfile.mp3";
        $fileUrl2 = "http://www.amazon.co.us/myfile.mp3";
        $saveDigitsCallback = "http://193.165.32.14:8080/api/digits";
        $response = "<?xml version\"1.0\" ?><Response><Play url=\"$fileUrl2\" /><GetDigits timeout=\"15\" callbackUrl=\"$saveDigitsCallback\"><Play url=\"$fileUrl\" /></GetDigits></Response>";
        return $response;
    }
    public function saveDigits(){
        // Save to DB ...
    }
    public function update_care_giver(Request $request){
            $client_id=$request->input('id');
            $phone_number =$request->input('phone_number');
            $date_recruitment=$request->input('date_recruitment');
            $id_number=$request->input('id_number');
			//if message_type is 1 = sms,2=voice
            $message_type=$request->input('message_type');
            

            
            $client = Client::find($client_id);
            $client->phone_number =  $phone_number;
            $client->date_recruitment = $date_recruitment;
            $client->message_type = $message_type;
            $client->id_number = $id_number;
            $client->updated_at =  date("Y-m-d H:i:s");
            if($client->save()){
                return response()->json($client);
            }
            return response()->json(['messages' => ['Error in saving']], 404);
 
        // Save to DB ...
    }

    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if(!empty($user)){
            if (Auth::attempt(array('email' => $request->email, 'password' => $request->password))) {

                return response()->json($user);
                
            } else {
                return response(["messages" => ["Invalid login credentials", "Wrong email and password combination"]], 403);
            }
        }else{
            return response(["messages" => ["Email does not exist in the system"]], 404);
        }
    }

    public function get_care_givers(Request $request){

        $user_id =$request->input('user_id');
  
        
        $clients = Client::where('user_id', $user_id)->get();

            return response()->json($clients);
        

    // Save to DB ...
}

    
	public function add_care_giver(Request $request){
			//$date = date('Y-m-d H:i:s', time());
			$id_number=$request->input('id_number');
            $phone_number =$request->input('phone_number');
            $date_recruitment=$request->input('recruitment_date');
			$user_id =$request->input('user_id');
			//if message_type is 1 = sms,2=voice
			$message_type=$request->input('message_type');
        
            
            	//step 2 save new client_details
						
					$client = new Client;
					$client->phone_number =  $phone_number;
                    $client->date_recruitment = $date_recruitment;
                    $client->id_number = $id_number;
					$client->user_id  = $user_id;
                    $client->message_type = $message_type;
                    $client->updated_at =  date("Y-m-d H:i:s");
					$client->created_at =  date("Y-m-d H:i:s");
				

				
					if($client->save()){
                        //step 3 send welcome messsage
                        	
						$msg = "Welcome to Nut App";                 
                        $this->send($phone_number,$msg);
						//$sender = new SenderController;
                        //$sender->send($to, $msg);

                        //step 4 get fourth day message
                       
                    $date_today = date("d-m-Y");
                    $fourth_message = EducationalMsg::where('sequence', 0)->first();
                    $fourth_message_date = date('d-m-Y', strtotime($date_today. ' + 4 days'));


					$message = new OutgoingMsg;
					$message->message =  $fourth_message->message;
					$message->destination =  $phone_number;
					$message->send_date  = $fourth_message_date;
                    $message->client_id = $client->client_id;
                    $message->status = 0;
                    $message->educational_message_id = $fourth_message->educational_message_id;
                    $message->updated_at =  date("Y-m-d H:i:s");
					$message->created_at =  date("Y-m-d H:i:s");
                    
				

				
					if($message->save()){
                        
                        for ($i = 1; $i < 12; $i++) {

                            $message_content = EducationalMsg::where('sequence', $i)->first();
                            $message_date = date('d-m-Y', strtotime($fourth_message_date.' + ' . $i . 'week'));

                            $subsquent_message = new OutgoingMsg;
                            $subsquent_message->message =  $message_content->message;
                            $subsquent_message->destination =  $phone_number;
                            $subsquent_message->send_date  = $message_date;
                            $subsquent_message->client_id = $client->client_id;
                            $subsquent_message->status = 0;
                            $subsquent_message->educational_message_id = $message_content->educational_message_id;
                            $subsquent_message->updated_at =  date("Y-m-d H:i:s");
                            $subsquent_message->created_at =  date("Y-m-d H:i:s");
                            if($subsquent_message->save()){
                                return response()->json($subsquent_message);

                                //echo "success";
                             }
                        }

                    }

            }
		}

		
	

}
