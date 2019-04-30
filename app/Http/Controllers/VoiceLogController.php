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
use App\IncomingMsg;
use Illuminate\Support\Facades\Auth;

class VoiceLogController extends Controller {

    //

    public function send($to, $message) {
        // $to=$request->input('to');
        // $message =$request->input('message');

        $username = "mhealthkenya";
        $apiKey = "9318d173cb9841f09c73bdd117b3c7ce3e6d1fd559d3ca5f547ff2608b6f3212";
        $AT = new AfricasTalking($username, $apiKey);

        // Get one of the services
        $sms = $AT->sms();
        // Use the service
        $send = $sms->send([
            'from' => '40149',
            'to' => $to,
            'message' => $message
        ]);

        return $send['status'];
    }

    public function sendVoice() {
        //app credentials
        $username = "mhealthkenya";
        $apiKey = "9318d173cb9841f09c73bdd117b3c7ce3e6d1fd559d3ca5f547ff2608b6f3212";
        //initialize the SDK
        $AT = new AfricasTalking($username, $apiKey);

        //get the voice service 
        $voice = $AT->voice();

        // Set your Africa's Talking phone number in international format
        $from = "+254711082608";
        //set number you want to call, comma separated list if more than one
//        $to = "+254728802160";
        $to = "+254705255873";

        try {
            //    Make the call
            $send = $voice->call([
                'from' => $from,
                'to' => $to
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
            echo "Error: " . $e->getMessage();
        }

        if ($send) {
            $voice = new VoiceLog;
            $voice->phoneNumber = $to;
        }
    }

    public function voice_receiver() {

        try {

            $items = OutgoingMsg::where('status', 0)->get();

            $today = date("d-m-Y");

            foreach ($items as $item) {

                $id = $item->outgoing_message_id;
                $sendDate = $item->send_date;
                $msg = $item->message;
                $to = $item->destination;
                // echo "Send data-> ".$sendDate." Leo ".$today."</br>";

                if ($sendDate == $today) {
                    // echo "Phone => ".$to." MSG ".$msg."</br>";
                    return $this->voice($msg);
                }
            }
        } catch (Exception $e) {
            echo "Ooops ni kubaya => " . $e;
        }
    }

    //Start  voice
    public function voice($msg) {

        $response = '<?xml version="1.0" encoding="UTF-8"?>';
        $response .= '<Response>';
        $response .= '<GetDigits timeout="2" numDigits="10"  callbackUrl="http://41.215.81.58:4500/api/savedigits">';
        $response .= '<Say>' . $msg . '</Say>';
        $response .= '</GetDigits>';
        $response .= '<Say>We did not receive any input. Good bye</Say>';
        $response .= '</Response>';

        header('Content-type: text/plain');
        echo $response;
    }

    //Process responses from voice
    public function saveDigits() {
        // Read the dtmf digits
        $dgts = $_POST['dtmfDigits'];

        if ($dgts == 1) {

            $to = '0728802160';
            $msgid = rand(10, 100);

            $text = "Thank you for your response";

            $response = '<?xml version = "1.0" encoding = "UTF-8" ?>';
            $response .= '<Response>';
            $response .= '<Say>' . $text . '</Say>';
            $response .= '</Response>';

            // Print the response onto the page so that our gateway can read it
            header('Content-type: text/plain');
            // echo $response;

            OutgoingMsg::where('destination', $to)
                    ->update(['response' => $dgts]);

            return $response;
        } else {

            $text = "Kindly type one";


            $response = '<?xml version="1.0" encoding="UTF-8"?>';
            $response .= '<Response>';
            $response .= '<GetDigits timeout="2" numDigits="10"  callbackUrl="http://41.215.81.58:4500/api/savedigits">';
            $response .= '<Say>' . $text . '</Say>';
            $response .= '</GetDigits>';
            $response .= '<Say>We did not receive any input. Good bye</Say>';
            $response .= '</Response>';

            // Print the response onto the page so that our gateway can read it
            header('Content-type: text/plain');
            // echo $response;

            return $response;
        }
    }

    public function voices_receiver() {

        //$fileUrl = "http://www.amazon.co.us/mypromptfile.mp3";
        // $fileUrl2 = "http://www.amazon.co.us/myfile.mp3";
        //$saveDigitsCallback = "http://193.165.32.14:8080/api/digits";
        $path = 'http://localhost:4500/diamond.mp3';
        $audio = 'diamond.mp3';

        $fileUrl2 = File::get($path);

        // return $response;

        $response = '<? xml version = "1.0" encoding = "UTF-8" ?>';
        $response .= '<Response>';
        $response .= '<Say>Play my new release</Say>';
        $response .= '<Play url="https://www.soundhelix.com/examples/mp3/SoundHelix-Song-1.mp3"/>';
        $response .= '</Response>';
// Print the response onto the page so that our gateway can read it
        header('Content-type: apllication/xml');
        echo $response;
    }

    public function update_care_giver(Request $request) {
        $client_id = $request->input('id');
        $phone_number = $request->input('phone_number');
//$date_recruitment=$request->date("Y-m-d H:i:s");
        $id_number = $request->input('id_number');
//if message_type is 1 = sms,2=voice
        $message_type = $request->input('message_type');



        $client = Client::find($client_id);
        $client->phone_number = (int) $phone_number;
//  $client->date_recruitment = $date_recruitment;
        $client->message_type = (int) $message_type;
        $client->id_number = (int) $id_number;
        $client->updated_at = date("Y-m-d H:i:s");
        if ($client->save()) {
// $data = array(
//     // 'counties' => $counties,
//      //'subcounties' => $subcounties,
//      'result' => $client,
// );
            return response("Updated succesfuly");
        }

        return response("Error in saving");


// Save to DB ...
    }

    public function login(Request $request) {
        $user = User::where('email', $request->email)->first();
        if (!empty($user)) {
            if (Auth::attempt(array('email' => $request->email, 'password' => $request->password))) {
                $user_array = array($user);

                $data = array(
// 'counties' => $counties,
//'subcounties' => $subcounties,
                    'result' => $user_array,
                );
                return response()->json($data);
            } else {
                return response("Invalid login credentials");
            }
        } else {
            return response("Email does not exist in the system");
        }
    }

    public function get_care_givers(Request $request) {

        $user_id = $request->input('user_id');


        $clients = Client::where('user_id', $user_id)->get();

        $data = array(
// 'counties' => $counties,
//'subcounties' => $subcounties,
            'result' => $clients,
        );
        return response()->json($data);



// Save to DB ...
    }

    public function add_care_giver(Request $request) {
//$date = date('Y-m-d H:i:s', time());
        $id_number = $request->input('id_number');
        $phone_number = $request->input('phone_number');
//$date_recruitment=$request->input('recruitment_date');
        $user_id = $request->input('user_id');
//if message_type is 1 = sms,2=voice
        $message_type = $request->input('message_type');


//step 2 save new client_details

        $client = new Client;
        $client->phone_number = (int) $phone_number;
//$client->date_recruitment = date("Y-m-d H:i:s");
        $client->id_number = (int) $id_number;
        $client->user_id = $user_id;
        $client->message_type = (int) $message_type;
        $client->updated_at = date("Y-m-d H:i:s");
        $client->created_at = date("Y-m-d H:i:s");



        if ($client->save()) {
//step 3 send welcome messsage

            $msg = "Habari Mzazi, Shukrani kwa kujisajili katika Nutrition App.";
            $this->send($phone_number, $msg);
//$sender = new SenderController;
//$sender->send($to, $msg);
//step 4 get fourth day message

            $date_today = date("d-m-Y");
            $fourth_message = EducationalMsg::where('sequence', 0)->first();
            $fourth_message_date = date('d-m-Y', strtotime($date_today . ' + 4 days'));


            $message = new OutgoingMsg;
            $message->message = $fourth_message->message;
            $message->destination = $phone_number;
            $message->send_date = $fourth_message_date;
            $message->client_id = $client->client_id;
            $message->status = 0;
            $message->educational_message_id = $fourth_message->educational_message_id;
            $message->updated_at = date("Y-m-d H:i:s");
            $message->created_at = date("Y-m-d H:i:s");




            if ($message->save()) {

                for ($i = 1; $i < 12; $i++) {

                    $message_content = EducationalMsg::where('sequence', $i)->first();
                    $message_date = date('d-m-Y', strtotime($fourth_message_date . ' + ' . $i . 'week'));

                    $subsquent_message = new OutgoingMsg;
                    $subsquent_message->message = $message_content->message;
                    $subsquent_message->destination = $phone_number;
                    $subsquent_message->send_date = $message_date;
                    $subsquent_message->client_id = $client->client_id;
                    $subsquent_message->status = 0;
                    $subsquent_message->educational_message_id = $message_content->educational_message_id;
                    $subsquent_message->updated_at = date("Y-m-d H:i:s");
                    $subsquent_message->created_at = date("Y-m-d H:i:s");
                    if ($subsquent_message->save()) {
//echo response()->json($subsquent_message);
//echo "success";
                    }
                }
            }
            return response("Saved Succesfuly");
        } else {
            return response("Error in saving");
        }
    }

    public function wellsms() {

        try {
            $items = OutgoingMsg::where('status', 0)->get();

            $today = date("d-m-Y");

            foreach ($items as $item) {


                $id = $item->outgoing_message_id;
                $sendDate = $item->send_date;
                $message = $item->message;
                $to = $item->destination;

// echo "Send data-> ".$sendDate." Leo ".$today."</br>";

                if ($sendDate == $today) {

                    echo "Phone => " . $to . " MSG " . $message . "</br>";

                    $sender = new SenderController;
                    $send_msg = $sender->send($to, $message);


                    if ($send_msg === false) {
//Error has occured....
                    } else {
//Success posting the  message ...
                        OutgoingMsg::where('outgoing_message_id', $id)
                                ->update(['status' => "1"]);
                    }
                }
            }
        } catch (Exception $e) {
            echo "Ooops ni kubaya => " . $e;
        }
    }

//Test messages.
    function educative() {

        $fourth_message_date = '2019-04-23';

        for ($i = 1; $i < 12; $i++) {

            $message_content = EducationalMsg::where('sequence', $i)->first();
            $message_date = date('d-m-Y', strtotime($fourth_message_date . ' + ' . $i . 'week'));

            $subsquent_message = new OutgoingMsg;
            $subsquent_message->message = $message_content->message;
            $subsquent_message->destination = 0716531426;
            $subsquent_message->send_date = $message_date;
            $subsquent_message->client_id = 7;
            $subsquent_message->status = 0;
            $subsquent_message->educational_message_id = $message_content->educational_message_id;
            $subsquent_message->updated_at = date("Y-m-d H:i:s");
            $subsquent_message->created_at = date("Y-m-d H:i:s");
// print_r($subsquent_message);

            if ($subsquent_message->save()) {
                echo response()->json($subsquent_message);

//echo "success";
            }
        }
    }

    function jibu() {


        try {

            $items = IncomingMsg::where('status', 0)->get();

            $today = date("d-m-Y");

            foreach ($items as $item) {


                $inid = $item->id;
                $message = $item->response;
                $dest = $item->source;

                $mobileno = substr($dest, 4);

                $len = strlen($mobileno);

                if ($len < 10) {

                    $to = "0" . $mobileno;
                } else {
                    $to = $dest;
                }
// echo " Phone ".$to." MSG ".$message. '</br>';

                $src = OutgoingMsg::where('destination', $to)->first();

// print_r($src);
// $outid = $src[id]; 
                $msgid = $src['educational_message_id'];



                if ($message == 1) {
//Update responses accordingly ...
                    echo "Phone " . $to . " MSG IDs " . $msgid . '</br>';
                    IncomingMsg::where('id', $inid)
                            ->update(['status' => "1", 'seqnc_id' => $msgid]);

//Update Response on outgoing table
                    OutgoingMsg::where('destination', $to)
                            ->update(['response' => $msgid]);
                } else {
                    IncomingMsg::where('id', $inid)
                            ->update(['status' => "1"]);
                }
            }
        } catch (Exception $e) {
            echo "Ooops ni kubaya => " . $e;
        }
    }

}
