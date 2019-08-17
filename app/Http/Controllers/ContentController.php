<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\Content;
use App\Friend;
use App\Comment;
use App\Love;
use Auth;
use DB;
use Illuminate\Support\Facades\Input;

class ContentController extends Controller
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
        $content= User::select('users.id', 'users.name', 'content.content_id', 'content.content', 'content.file', 'content.time', DB::raw('COUNT(love.content_id) AS love'), DB::raw('COUNT(comment.comment) AS comment'))
        ->join('content', 'users.id', '=', 'content.id', 'left outer')
        ->join('love', 'content.content_id', '=', 'love.content_id', 'left outer')
        ->join('comment', 'content.content_id', '=', 'comment.content_id', 'left outer')
        ->where('content.content_id', $name)->first();
        
        $love= Love::select('love.id', 'users.name')
        ->join('users', 'love.id', '=', 'users.id', 'left outer')
        ->join('content', 'love.content_id', '=', 'content.content_id', 'left outer')
        ->where('content.content_id', $name)->get();

        $comment= User::select('users.name', 'comment.comment')
        ->join('content', 'users.id', '=', 'content.id', 'left outer')
        ->join('comment', 'content.content_id', '=', 'comment.content_id', 'left outer')
        ->where('content.content_id', $name)->get();

        $love_c = Love::select('id')->where('content_id', $name)->where('id', $id)->count();
        if($love_c == 0) {
            $btn = "";
        }else if($love_c == 1) {
            $btn = "fg-blue";
        };

        return view('content', [
            'content' => $content,
            'love' => $love,
            'btn' => $btn,
            'comment' => $comment,
        ]);
    }

    public function store_love(Request $request) {

        $this->validate($request, [
            'content_id' => ['required', 'string', 'max:10'],
            'id' => ['required', 'string', 'max:10'],
        ]);

        $love_c = Love::select('id')
        ->where('content_id', $request['content_id'])
        ->where('id', $request['id'])
        ->count(); 
        if($love_c == 0) {
            Love::create([
                'content_id' => $request['content_id'],
                'id' => $request['id'],
            ]);
        }else if($love_c == 1) {
            Love::where([
                'content_id' => $request['content_id'],
                'id' => $request['id'],
            ])->delete();
        };
        
        return redirect()->back();
    }

    public function store_comment(Request $request) {

        $this->validate($request, [
            'content_id' => ['required', 'string', 'max:10'],
            'id' => ['required', 'string', 'max:10'],
            'comment' => ['required', 'string', 'max:255'],
        ]);

        Comment::create([
            'content_id' => $request['content_id'],
            'id' => $request['id'],
            'comment' => $request['comment'],
        ]);
        
        return redirect()->back();
    }
}
