<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\Content;
use App\Love;
use DB;

use Illuminate\Support\Facades\Input;

class JSONController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    /*
    public function __construct()
    {
        $this->middleware('auth');
    }
    */

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        //
    }

    public function home()
    {
        //$user = Content::paginate(5);
        $user = User::select('users.id', 'users.name', 'content.content_id', 'content.content', 'content.file', 'content.time', DB::raw('COUNT(love.id) AS love'), DB::raw('COUNT(comment.comment) AS comment'))
        ->join('content', 'users.id', '=', 'content.id', 'left outer')
        ->join('love', 'content.content_id', '=', 'love.content_id', 'left outer')
        ->join('comment', 'content.content_id', '=', 'comment.content_id', 'left outer')
        ->groupBy('content.content_id')
        ->orderBy('content.time', 'DESC')->paginate(20);
                
        return response()->json($user);
    }
}
