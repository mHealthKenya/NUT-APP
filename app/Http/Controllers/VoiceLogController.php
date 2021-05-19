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
use App\EducationalVoice;
use App\OutgoingVoice;
use Carbon\Carbon;

class VoiceLogController extends Controller
{

    public function send($to, $message)
    {
        // $to=$request->input('to');
        // $message =$request->input('message');

        $username = "mhealthkenya";
        $apiKey = "a134a43032612487eb3f4d5fcc4c6c7538d56afdd871dcd284ab171d778c4e51";
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

    public function sendVoice()
    {

        $items = OutgoingVoice::where('status', 0)->get();

        $today = date("d-m-Y");

        foreach ($items as $item) {

            $id = $item->outgoing_message_id;
            echo $sendDate = $item->send_date;
            $msg = $item->message;
            $dest = $item->destination;

            $mobileno = substr($dest, 1);

            $len = strlen($mobileno);

            if ($len < 10) {
                $to = "+254" . $mobileno;
            } else {
                $to = $dest;
            }

            // echo "Send data-> ".$sendDate." Leo ".$today."</br>";
            if ($sendDate == $today) {
                echo "Phone => " . $to . " MSG " . $msg . "</br>";
                //app credentials
                $username = "mhealthkenya";
                $apiKey = "a134a43032612487eb3f4d5fcc4c6c7538d56afdd871dcd284ab171d778c4e51";
                //initialize the SDK
                $AT = new AfricasTalking($username, $apiKey);

                //get the voice service 
                $voice = $AT->voice();

                // Set your Africa's Talking phone number in international format
                $from = "+254711082608";
                //set number you want to call, comma separated list if more than one
                // $to = "+254728802160";
                //        $to = "+254705255873";
                //        $to = "+254728802160";


                try {
                    //  Make the call
                    $send = $voice->call([
                        'from' => $from,
                        'to' => $to
                    ]);
                    // $results = $voice->call($from, $to);
                    //                    print_r($send);
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
        }
    }

    //Callback url- Play the audio file 
    public function voice_receiver()
    {

        try {
            // This is a unique ID generated for this call
            $sessionId = $_POST['sessionId'];

            // Check to see whether this call is active
            $isActive = $_POST['isActive'];

            $dest = $_POST['callerNumber']; //This is the clients' mobile number i.e Check to see whether this is the enqueue or dequeue Africas Talking phone number  

            $direction = $_POST['direction'];

            switch ($isActive) {
                case 1:
                    switch ($direction) {
                            //Calls made by App/System
                        case "Outbound":

                            $items = OutgoingVoice::where('status', 0)->get();

                            if ($items) {

                                $today = date("d-m-Y");

                                foreach ($items as $item) {

                                    $id = $item->outgoing_message_id;
                                    $sendDate = $item->send_date;
                                    $msg = $item->message;
                                    $orig = $item->destination;

                                    $mobileno = substr($orig, 1);

                                    $len = strlen($mobileno);

                                    if ($len < 10) {
                                        $to = "+254" . $mobileno;
                                    } else {
                                        $to = $dest;
                                    }

                                    // echo "Send data-> ".$sendDate." Leo ".$today."</br>";

                                    if ($sendDate == $today) {
                                        // echo "Phone => ".$to." MSG ".$msg."</br>";
                                        //                        return $this->voice($msg, $id, $to);
                                        if ($dest == $to) {

                                            // update response on outgoing table            

                                            OutgoingVoice::where('outgoing_message_id', $id)
                                                ->update(['sessionId' => $sessionId]);

                                            $response = '<?xml version="1.0" encoding="UTF-8"?>';
                                            $response .= '<Response>';
                                            $response .= '<GetDigits timeout="3" numDigits="10"  callbackUrl="http://41.215.81.58:4500/api/savedigits">';
                                            $response .= '<Play url=" ' . $msg . ' "/>';
                                            $response .= '</GetDigits>';
                                            $response .= '<Say>We did not receive any input. Good bye!</Say>';
                                            $response .= '</Response>';

                                            header('Content-type: text/plain');

                                            echo $response;
                                        }
                                    }
                                }
                            }

                            break;
                            //Calls made by client
                        case "Inbound":
                            $mobileno = substr($dest, 4);

                            $len = strlen($dest);

                            if ($len <= 13) {
                                $to = "0" . $mobileno;
                            } else {
                                $to = $dest;
                            }

                            // update response on outgoing table            
                            OutgoingVoice::where('destination', $to)
                                ->update(['sessionId' => $sessionId]);

                            //                            $msg = "Press 1 to replay todays message, 2 to replay previous messsages";
                            $msg = 'https://sutholo.com/mp3/thy.mp3';
                            $text = "Received thank you";

                            $response = '<?xml version="1.0" encoding="UTF-8"?>';
                            $response .= '<Response>';
                            $response .= '<GetDigits timeout="3" numDigits="10"  callbackUrl="http://41.215.81.58:4500/api/savedigits">';
                            $response .= '<Play url=" ' . $msg . ' "/>';
                            $response .= '</GetDigits>';
                            $response .= '<Say>We did not receive any input. Good bye!</Say>';
                            $response .= '</Response>';

                            header('Content-type: text/plain');

                            echo $response;

                            break;

                        default:

                            break;
                    }

                    break;

                default:

                    $duration = $_POST['durationInSeconds'];
                    $code = $_POST['currencyCode'];
                    $amount = $_POST['amount'];

                    //  storing this information in the database for records

                    OutgoingVoice::where('outgoing_message_id', $id)
                        ->update(['sessionId' => $sessionId], ['durationInSeconds' => $duration], ['currencyCode' => $code], ['amount' => $amount]);
                    break;
            }
        } catch (Exception $e) {
            echo "Ooops ni kubaya => " . $e;
        }
    }

    //Process and save voice responses from client
    public function saveDigits()
    {

        // Read the dtmf digits
        $dgts = $_POST['dtmfDigits'];

        // This is a unique ID generated for this call
        $sessId = $_POST['sessionId'];

        $direction = $_POST['direction'];

        $dest = $_POST['callerNumber'];

        $mobileno = substr($dest, 4);

        $len = strlen($mobileno);

        if ($len < 10) {

            $to = "0" . $mobileno;
        } else {

            $to = $dest;
        }

        $items = OutgoingVoice::where('sessionId', $sessId)->get();

        if ($items) {

            foreach ($items as $item) {

                $sessionId = $item->sessionId;


                switch ($direction) {
                        // Calls made by App/System
                    case "Outbound":

                        switch ($dgts) {
                            case 1:

                                $msg = "Response received, Thank you!";

                                $response = '<?xml version = "1.0" encoding = "UTF-8" ?>';
                                $response .= '<Response>';
                                $response .= '<Say>' . $msg . '</Say>';
                                $response .= '</Response>';

                                // Print the response onto the page so that our gateway can read it
                                header('Content-type: text/plain');

                                // update response on outgoing table            
                                OutgoingVoice::where('sessionId', $sessionId)
                                    ->update(['response' => $dgts, 'status' => 1]);

                                return $response;


                                break;

                            default:

                                $text = "Invalid, Kindly press one";

                                $response = '<?xml version="1.0" encoding="UTF-8"?>';
                                $response .= '<Response>';
                                $response .= '<GetDigits timeout="3" numDigits="10"  callbackUrl="http://41.215.81.58:4500/api/savedigits">';
                                $response .= '<Say>' . $text . '</Say>';
                                $response .= '</GetDigits>';
                                $response .= '<Say>We did not receive any input. Good bye</Say>';
                                $response .= '</Response>';

                                // Print the response onto the page so that our gateway can read it
                                header('Content-type: text/plain');
                                // echo $response;

                                return $response;

                                break;
                        }

                        break;

                        // Calls made by client
                    case "Inbound":

                        switch ($dgts) {
                            case '1':


                                $text = 'http://sutholo.com/mp3/1.mp3';

                                $response = '<?xml version="1.0" encoding="UTF-8"?>';
                                $response .= '<Response>';
                                $response .= '<GetDigits timeout="3" numDigits="10"  callbackUrl="http://41.215.81.58:4500/api/savedigits">';
                                $response .= '<Play url=" ' . $text . ' "/>';
                                $response .= '</GetDigits>';
                                $response .= '<Say>We did not receive any input. Good bye</Say>';
                                $response .= '</Response>';

                                // Print the response onto the page so that our gateway can read it
                                header('Content-type: text/plain');

                                // update response on outgoing table            
                                OutgoingVoice::where('sessionId', $sessionId)
                                    ->update(['response' => $dgts, 'status' => 1]);

                                return $response;

                                break;

                            case '2':

                                $items = OutgoingVoice::where('destination', $to)->get();

                                if ($items) {


                                    foreach ($items as $item) {

                                        $id = $item->outgoing_message_id;

                                        $geturl = OutgoingVoice::find($id);
                                        $sendDate = $item->send_date;
                                        $msg = $geturl->educational_voice->voice_url;
                                        $orig = $item->destination;
                                        $sessIds = $item->sessionId;

                                        $date = strtotime(date($sendDate));

                                        $today = strtotime(date("d-m-Y"));

                                        if ($date < $today) {

                                            $response = '<?xml version="1.0" encoding="UTF-8"?>';
                                            $response .= '<Response>';
                                            $response .= '<GetDigits timeout="10" numDigits="10"  callbackUrl="http://41.215.81.58:4500/api/savedigits">';
                                            $response .= '<Play url=" ' . $msg . ' "/>';
                                            $response .= '</GetDigits>';
                                            $response .= '<Say>No Further input. Good bye</Say>';
                                            $response .= '</Response>';

                                            // Print the response onto the page so that our gateway can read it
                                            header('Content-type: text/plain');

                                            // update response on outgoing table            
                                            OutgoingVoice::where('outgoing_message_id', $id)
                                                ->update(['response' => $dgts, 'status' => 1]);

                                            return ($response);

                                            //                                            echo 'Phone ' . $orig . ' MSGs ' . $msg . ' Send date ' . $sendDate . ' Session ' . $id . '</br>';
                                        }
                                        //                                        
                                    }
                                }

                                break;

                            default:

                                $text = "Invalid, Kindly press one to listen to todays message, two to listen to previous messages";

                                $response = '<?xml version="1.0" encoding="UTF-8"?>';
                                $response .= '<Response>';
                                $response .= '<GetDigits timeout="3" numDigits="10"  callbackUrl="http://41.215.81.58:4500/api/savedigits">';
                                $response .= '<Say>' . $text . '</Say>';
                                $response .= '</GetDigits>';
                                $response .= '<Say>We did not receive any input. Good bye</Say>';
                                $response .= '</Response>';

                                // Print the response onto the page so that our gateway can read it
                                header('Content-type: text/plain');
                                // echo $response;

                                return $response;
                                break;
                        }

                        break;

                    default:
                        break;
                }
            }
        }
    }

    public function update_care_giver(Request $request)
    {
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

    public function login(Request $request)
    {
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

    public function get_care_givers(Request $request)
    {

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

    public function add_care_giver(Request $request)
    {

        $id_number = $request->input('id_number');
        $phone_number = $request->input('phone_number');
        $user_id = $request->input('user_id');
        $message_type = $request->input('message_type');
        $client = new Client;
        $client->phone_number = (int) $phone_number;
        $client->id_number = (int) $id_number;
        $client->user_id = $user_id;
        $client->message_type = (int) $message_type;
        $client->updated_at = date("Y-m-d H:i:s");
        $client->created_at = date("Y-m-d H:i:s");



        if ($client->save()) {
            $msg = "Habari Mzazi, Shukrani kwa kujisajili katika Nutrition App.";
            $this->send($phone_number, $msg);

            if ($message_type == 1) {
                $date_today = Carbon::today();
                //date faker
                // $date_today = Carbon::create(2020, 4, 01);
                // Carbon::setTestNow($date_today);
                $fourth_message = EducationalMsg::where('sequence', 0)->first();
                $fourth_message_date = $date_today->addDays(4);



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
                        $message_date = Carbon::parse($fourth_message_date)->addWeeks($i);
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
                            echo 'saved';
                        }
                    }
                }
                return response("Saved Succesfuly");
            } else {
                $date_today = Carbon::today();
                $fourth_message = EducationalVoice::where('sequence', 0)->first();
                $fourth_message_date = $fourth_message_date = $date_today->addDays(4);


                $message = new OutgoingVoice;
                $message->message = $fourth_message->voice_url;
                $message->destination = $phone_number;
                $message->send_date = $fourth_message_date;
                $message->client_id = $client->client_id;
                $message->status = 0;
                $message->educational_message_id = $fourth_message->voice_message_id;
                $message->updated_at = date("Y-m-d H:i:s");
                $message->created_at = date("Y-m-d H:i:s");

                if ($message->save()) {

                    for ($i = 1; $i < 12; $i++) {

                        $message_content = EducationalVoice::where('sequence', $i)->first();
                        $message_date = Carbon::parse($fourth_message_date)->addWeeks($i);

                        $subsquent_message = new OutgoingVoice;
                        $subsquent_message->message = $message_content->voice_url;
                        $subsquent_message->destination = $phone_number;
                        $subsquent_message->send_date = $message_date;
                        $subsquent_message->client_id = $client->client_id;
                        $subsquent_message->status = 0;
                        $subsquent_message->educational_message_id = $message_content->voice_message_id;
                        $subsquent_message->updated_at = date("Y-m-d H:i:s");
                        $subsquent_message->created_at = date("Y-m-d H:i:s");
                        if ($subsquent_message->save()) {
                        }
                    }
                }
                return response("Saved Succesfuly");
            }
        } else {
            return response("Error in saving");
        }
    }


    public function wellsms()
    {

        try {
            $items = OutgoingMsg::where('status', 0)->get();
            $today = Carbon::today()->toDateString();

            foreach ($items as $item) {


                $id = $item->outgoing_message_id;
                $sendDate = date('Y-m-d', strtotime($item->send_date));
                $message = $item->message;
                $to = $item->destination;

                if ($sendDate == $today) {
                    $sender = new SenderController;
                    $send_msg = $sender->send($to, $message);
                    if ($send_msg === false) {
                        //Error has occured....
                    } else {
                        OutgoingMsg::where('outgoing_message_id', $id)
                            ->update(['status' => "1"]);
                        echo "Success sending SMS...";
                    }
                }
            }
        } catch (Exception $e) {
            echo "Ooops ni kubaya => " . $e;
        }
    }
    

    public function rewellsms()
    {

        try {
            $items = OutgoingMsg::where('status', 0)->get();
            $today = Carbon::today()->toDateString();

            foreach ($items as $item) {


                $id = $item->outgoing_message_id;
                $sendDate = date('Y-m-d', strtotime($item->send_date));
                $message = $item->message;
                $to = $item->destination;

                if ($sendDate < $today) {
                    $sender = new SenderController;
                    $send_msg = $sender->send($to, $message);
                    if ($send_msg === false) {
                        //Error has occured....
                    } else {
                        OutgoingMsg::where('outgoing_message_id', $id)
                            ->update(['status' => "1"]);
                        echo "Success sending SMS...";
                    }
                }
            }
        } catch (Exception $e) {
            echo "Ooops ni kubaya => " . $e;
        }
    }

    public function sendunsent()
    {
        echo "here it is";

        try {
            $items = OutgoingMsg::where('status', 0)->get();

            $today = Carbon::today()->toDateString();

            foreach ($items as $item) {


                $id = $item->outgoing_message_id;
                $sendDate = date('Y-m-d', strtotime($item->send_date));
                $message = $item->message;
                $to = $item->destination;
                if ($sendDate < $today) {

                    echo "Phone => " . $to . " MSG " . $message . " ID " . $id . "</br>";
                    $sender = new SenderController;
                    $send_msg = $sender->send($to, $message);


                    if ($send_msg === false) {
                        echo 'Error has occured....';
                    } else {
                        OutgoingMsg::where('outgoing_message_id', $id)
                            ->update(['status' => "1"]);
                        echo "Success sending SMS...";
                    }
                }
            }
        } catch (Exception $e) {
            echo "Ooops ni kubaya => " . $e;
        }
    }

    function educative()
    {

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


            if ($subsquent_message->save()) {
                echo response()->json($subsquent_message);
            }
        }
    }

    function jibu()
    {


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

                $src = OutgoingMsg::where('destination', $to)->first();
                $msgid = $src['educational_message_id'];



                if ($message == 1) {
                    echo "Phone " . $to . " MSG IDs " . $msgid . '</br>';
                    IncomingMsg::where('id', $inid)
                        ->update(['status' => "1", 'seqnc_id' => $msgid]);
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

    function clean()
    {

        $dest = '+254735219899';

        $mobileno = substr($dest, 4);

        $len = strlen($mobileno);

        if ($len < 10) {

            $to = "0" . $mobileno;
        } else {
            $to = $dest;
        }




        $items = OutgoingVoice::where('destination', $to)->get();

        if ($items) {

            //                                    

            foreach ($items as $item) {

                $id = $item->outgoing_message_id;
                $sendDate = $item->send_date;
                $msg = $item->message;
                $orig = $item->destination;

                $date = strtotime(date($sendDate));

                $today = strtotime(date("d-m-Y"));

                if ($date < $today) {

                    echo 'MSGs ' . $msg . ' Send date ' . $sendDate . '</br>';
                }
            }
        }
    }
}
