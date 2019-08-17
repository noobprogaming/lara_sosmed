<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\Content;
use App\Friend;

use DB;
use Auth;
use Illuminate\Support\Facades\Input;

class ProfileController extends Controller
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
        $id = Auth::user()->id;
        $name = Input::get('q');

        //$user = Content::paginate(5);
        $user_profile = User::select('users.id', 'users.name', 'content.content_id', 'content.content', 'content.file', 'content.time', DB::raw('COUNT(love.id) AS love'), DB::raw('COUNT(comment.comment) AS comment'))
        ->join('content', 'users.id', '=', 'content.id', 'left outer')
        ->join('love', 'content.content_id', '=', 'love.content_id', 'left outer')
        ->join('comment', 'content.content_id', '=', 'comment.content_id', 'left outer')
        ->groupBy('content.content_id')
        ->where('users.id', $name)
        ->orderBy('content.time', 'DESC')
        ->paginate(20);
        $user_describe = User::select('id', 'name', 'email', 'created_at')->where('id', $name)->first();
        $friend_following = User::select('users.id', 'users.name')->join('friend', 'users.id', '=', 'friend.friend_id', 'left outer')->where('friend.user_id', $name)->get();
        $friend_followers = User::select('users.id', 'users.name')->join('friend', 'users.id', '=', 'friend.user_id', 'left outer')->where('friend.friend_id', $name)->get();
        $friend_following_c = Friend::select('user_id')->where('user_id', $name)->count(); 
        $friend_followers_c = Friend::select('friend_id')->where('friend_id', $name)->count(); 

        $follow_c = Friend::select('user_id')->where('user_id', $id)->where('friend_id', $name)->count();
        if($follow_c == 0) {
            $follow_c = "Follow";
            $btn = "btn-primary";
        }else if($follow_c == 1) {
            $follow_c = "Unfollow";
            $btn = "btn-danger";
        };

        $follow_me = Friend::select('user_id')->where('user_id', $name)->where('friend_id', $id)->count();
        if($follow_me == 0) {
            $follow_me = "";
        }else if($follow_me == 1) {
            $follow_me = "$name is following you!";
        };

        return view('profile', [
            'user_profile' => $user_profile, 
            'user_describe' => $user_describe, 
            'friend_following' => $friend_following,
            'friend_followers' => $friend_followers,
            'friend_following_c' => $friend_following_c,
            'friend_followers_c' => $friend_followers_c,
            'follow_c' => $follow_c,
            'btn' => $btn,
            'follow_me' => $follow_me,
        ]);
    }

    public function store_content(Request $request) {

        $this->validate($request, [
            'id' => ['required', 'string', 'max:10'],
            'content' => ['required', 'string', 'max:255'],
        ]);

        Content::create([
            'id' => $request['id'],
            'content' => $request['content'],
        ]);

        return redirect()->back();
    }

    public function delete_content($content_id) {        
        $user = Content::find($content_id);
        $user->delete();
        return redirect()->back();
    }

    public function store_follow(Request $request) {

        $this->validate($request, [
            'user_id' => ['required', 'string', 'max:10'],
            'friend_id' => ['required', 'string', 'max:10'],
        ]);

        $follow_c = Friend::select('user_id')
        ->where('user_id', $request['user_id'])
        ->where('friend_id', $request['friend_id'])
        ->count(); 
        if($follow_c == 0) {
            Friend::create([
                'user_id' => $request['user_id'],
                'friend_id' => $request['friend_id'],
            ]);
        }else if($follow_c == 1) {
            Friend::where([
                'user_id' => $request['user_id'],
                'friend_id' => $request['friend_id'],
            ])->delete();
        };
        
        return redirect()->back();
    }
}
