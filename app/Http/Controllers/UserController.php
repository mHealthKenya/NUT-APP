<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Facility;
use App\County;
use App\SubCounty;
use App\Client;

class UserController extends Controller
{
    //
    public function adduserform(){
        $facilities = Facility::all();
        $counties = County::all();
        $subcounties = SubCounty::all();

        $data = array(
            'facilities' => $facilities,
            'counties' => $counties,
            'subcounties' => $subcounties,

        );

        return view('clinician.addClinician')->with($data);
    }
    public function get_facilities(Request $request){
        $sub_county_id = $request->Sub_County_ID;

        $facilities = Facility::where('Sub_County_ID', $sub_county_id)->get();
        return $facilities;
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

                
              $msg = "Hello " . $request->input('first_name') . ", you have been registered successfully on the Nutrition Application System as a Clinician. " .
            "You can access the system at afyapoa.mhealthkenya.co.ke with Username:" . $request->input('email') . " and Password:" . $pwd;

               $to =  $fullno;
               $sender = new SenderController;
               $sender->send($to, $msg);


               };
               echo ("Clinician added successfuly");
                 return redirect()->route('viewClinician');

               // return response(['status' => 'success', 'details' => $user]);


            }
        } catch (Exception $e){
            return response(['status' => 'error']);
        }
    }
    public function viewclinician(){
        try {

            $facilities = Facility::all();
            $counties = County::all();
            $subcounties = SubCounty::all();
            //$clinician = Client::with('person.sub_county.county.region', 'person.userAccount')->with('type')->where('deleted', 0)->get();
            $clinician = User::all();

            $data = array(
                'facilities' => $facilities,
                'counties' => $counties,
                'subcounties' => $subcounties,
                'clinician' => $clinician,
    
            );


            return view('clinician.viewClinician')->with($data);

            // echo json_encode($agents);
        } catch (Exception $e) {
            return response(['status' => 'error']);
        }
    }
    public function editclinician(Request $request){
        try {

            date_default_timezone_set('UTC');
            $date = date('Y-m-d H:i:s', time());
            $pno = $request->input('phone_number');
            $str = substr($pno, 1);

            $fullno = "+254" . $str;
            $permitted_chars = '0123456789ABCDEFGHJKLMNPQRSTUVWXYZ';

            $pwd = substr(str_shuffle($permitted_chars), 0, 6);

            $user = User::find($request->input('user_id'));
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
            $user->save();

            return redirect()->route('viewClinician');

            
            

    } catch (Exception $e) {
        return response(['status' => 'error']);
    }
  }
    }

