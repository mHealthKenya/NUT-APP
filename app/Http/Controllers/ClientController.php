<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Client;
use App\Facility;

class ClientController extends Controller
{
    //
    public function addclientform(){
        $facilities = Facility::all();
        //$counties = County::all();
        //$subcounties = SubCounty::all();
        $client = Client::all();

        $data = array(
            'facilities' => $facilities,
           // 'counties' => $counties,
            //'subcounties' => $subcounties,
            'client' => $client,

        );

        return view('caregiver.viewcaregiver')->with($data);
    }
}
