<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\Content;
use App\Love;

use DB;
use Illuminate\Support\Facades\Input;

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
        //$user = Content::paginate(5);
        $user = User::select('users.id', 'users.name', 'content.content_id', 'content.content', 'content.file', 'content.time', DB::raw('COUNT(love.id) AS love'), DB::raw('COUNT(comment.comment) AS comment'))
        ->join('content', 'users.id', '=', 'content.id', 'left outer')
        ->join('love', 'content.content_id', '=', 'love.content_id', 'left outer')
        ->join('comment', 'content.content_id', '=', 'comment.content_id', 'left outer')
        ->groupBy('content.content_id')
        ->orderBy('content.time', 'DESC')->paginate(20);

        return view('home', [
            'user' => $user,
        ]);
    }

    public function store_content(Request $request) {

        $this->validate($request, [
            'id' => ['required', 'string', 'max:10'],
            'content' => ['nullable'],
            'file' => 'nullable',
        ]);
        
        if(!empty($request['file'])) {
            $q = Content::select('content_id')
            ->where('id', $request['id'])
            ->orderBy('content_id', 'DESC')
            ->first();
            $file = $request->file('file');
            $file->move('data_file', $q['content_id']);

            Content::create([
                'id' => $request['id'],
                'content' => $request['content'],
                'file' => $q['content_id'],
            ]);
        }else{
            Content::create([
                'id' => $request['id'],
                'content' => $request['content'],
            ]);
        }

        

        return redirect()->back();
    }

    public function delete_content($content_id) {        
        $user = Content::find($content_id);
        $user->delete();
        return redirect()->back();
    }
}
