<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    //
    public function adduserform(){
        return view('clinician.addClinician');
    }

    public function adduser(Request $request){
        try {
            if ($request->first_name == ''){
                return response(['status'=>'error', 'details'=>"Please enter first name"]);

            }
            if ($request->last_name == '') {
                return response(['status' => 'error', 'details' => "Please enter the Last name"]);
            } else {
                date_default_timezone_set('UTC');
                $date = date('Y-m-d H:i:s', time());

                $pno = $request->input('phone_number');
                $str = substr($pno, 1);

                $fullno = "+254" . $str;
                $permitted_chars = '0123456789ABCDEFGHJKLMNPQRSTUVWXYZ';

                $pwd = substr(str_shuffle($permitted_chars), 0, 6);

                $user = new User;
                $user->first_name = $request->input('first_name');
                $user->last_name = $request->input('last_name');
                $user->id_number = $request->input('id_number');
                $user->phone_number = $fullno;
                $user->facility_id = $request->input('facility_id');
                $user->password = bcrypt($pwd);
                $user->email = $request->input('email');
                $user->created_at = $date;
                $user->updated_at = $date;
               if  ($user->save()){

                
              $msg = "Hello " . $request->input('first_name') . ", you have been registered successfully on Nut APP System as a Clinician :). " .
            "You can access the system at afyapoa.mhealthkenya.co.ke with Username:" . $request->input('email') . " and Password:" . $pwd;

               $to =  $fullno;
               $sender = new SenderController;
               $sender->send($to, $msg);


               };
               echo ("Clinician added successfuly");
              // return redirect()->route('viewAgents');

                return response(['status' => 'success', 'details' => $user]);


            }
        } catch (Exception $e){
            return response(['status' => 'error']);
        }
    }
}
