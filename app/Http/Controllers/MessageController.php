<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\Message;

use DB;
use Illuminate\Support\Facades\Input;
use Auth;

class MessageController extends Controller
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
    public function index(Request $request)
    {   
        $id = Auth::user()->id;
        $name = Input::get('q');

        $user_describe = User::select('id', 'name')->where('id', $name)->first();

        /*
        $message_list = DB::select( 
        DB::raw("SELECT * FROM 
        (
        SELECT users.id, users.name, message.message
        FROM users
        LEFT OUTER JOIN message ON users.id = message.sender_id
        WHERE message.receiver_id = '$id'
        ORDER BY message.time DESC LIMIT 999999
        ) AS x GROUP BY x.id
        "));
        */

        $message_list = User::select('users.id', 'users.name')
        ->join('friend', 'users.id', '=', 'friend.friend_id', 'left outer')
        ->where('friend.user_id', $id)->get();

        $message_chat = User::select('users.id', 'users.name', 'message.message', 'message.time')
        ->join('message', 'users.id', '=', 'message.sender_id', 'left outer')
        ->whereIn('message.receiver_id', [$id, $name])
        ->WhereIn('message.sender_id', [$name, $id])
        ->orderBy('message.time', 'ASC')
        ->get();
        
        return view('message', [
            'user_describe' => $user_describe,
            'message_chat' => $message_chat, 
            'message_list' => $message_list,
        ]);
    }

    public function store_message(Request $request) {

        $this->validate($request, [
            'sender_id' => ['required', 'string', 'max:10'],
            'receiver_id' => ['required', 'string', 'max:10'],
            'message' => ['required', 'string', 'max:255'],
        ]);

        Message::create([
            'sender_id' => $request['sender_id'],
            'receiver_id' => $request['receiver_id'],
            'message' => $request['message'],
        ]);

        return redirect()->back();
    }
}
