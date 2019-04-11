<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Client;
use App\User;
use App\OutgoingMsg;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        $data = array(
            
            'clients_count' => Client::count(),
            'users_count' => User::count(),
            'messages_count' => OutgoingMsg::count(),

            
        );
        return view('dashboard.dashboardv2')->with($data);
        //echo json_encode($data);

    }
}
