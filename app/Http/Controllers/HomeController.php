<?php

namespace App\Http\Controllers;

use App\WorkOut;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $data['workouts'] = WorkOut::where('user_id', Auth::id())->get();
        return view('home')->with($data);
    }
}
