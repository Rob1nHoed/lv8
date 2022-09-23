<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = User::find(Auth::id());

        if($user != null){
            
        $received_files = $user->received;
        $uploaded_files = $user->files;  
        $downloaded_files = $user->downloaded;

        $userData = [
            'received' => $received_files,
            'uploaded' => $uploaded_files,
            'downloaded' => $downloaded_files,
        ];
        
        return view('home' , compact('userData'));
        }
        else
        {
            return view('home');
        }
    }
}
