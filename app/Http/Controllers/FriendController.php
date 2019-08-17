<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Friend;

use Illuminate\Support\Facades\Input;

class FriendController extends Controller
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

        $name = Input::get('q');

        //$user = Content::paginate(5);
        $friend_following = Friend::select('friend_id')
        ->where('user_id', $name)
        ->orderBy('friend_id', 'ASC')
        ->get();
    
        return view('profile', ['friend_following' => $friend_following]);
    }
}
