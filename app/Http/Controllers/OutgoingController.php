<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\OutgoingMsg;

class OutgoingController extends Controller
{
    //
    public function addmessagesform(){
        // $facilities = Facility::all();
        //$counties = County::all();
        //$subcounties = SubCounty::all();
        $client = OutgoingMsg::all();

        $data = array(
          
            'client' => $client,

        );

        return view('messages.viewMessages')->with($data);
    }
}
