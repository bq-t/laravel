<?php

namespace App\Http\Controllers;

use App;
use Illuminate\Http\Request;

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
    public function index(Request $request)
    {
        $id = $request->route('id');
        $user = App\User::findOrFail($id);
        $access = null;

        if(auth()->user() != null) {
            if($id != auth()->user()->id) {
                $access = App\LibAccess::where([['lib_id', auth()->user()->id], ['user_id', $id]])->first();
            }
        }

        if(!$request->has('reply')) {
            $reply = [];
        }

        if($request->has('replycom')) {
            $r_id = $request->com;
            $r_com = App\Comment::where('id', $r_id)->first();
            $r_user = App\User::find($r_com->user_id);

            $reply['id'] = $r_id;
            $reply['user'] = $r_user->name;
        }

        if(auth()->user() == NULL) {
            $auth = -1;
        }
        else {
            $auth = auth()->user()->id;
        }

        $get_comments = App\Comment::where('page_id', $id)->take(5)->orderBy('created_at')->get();
        $comments = App\Comment::CreateCommentsArray($get_comments, $auth);

        return view('profile', compact('user', 'comments', 'reply', 'access'));
    }

    public function create(Request $request) {

        $comment = new App\Comment;

        $comment->page_id = $request->page_id;
        $comment->user_id = auth()->user()->id;
        $comment->title = $request->title;
        $comment->theme = $request->theme;
        $comment->text = $request->text;

        $comment->save();

        if($request->has('reply_id')) {
            $quote = new App\CommentReply;

            $quote->comment_id = $comment->id;
            $quote->to_comment_id = $request->reply_id;

            $quote->save();
        }

        return back();
    }

    public function delete(Request $request) {

        $user_id = auth()->user()->id;
        $comment = App\Comment::where('id', $request->id);

        if($comment->first()->user_id == $user_id || $request->page_id == $user_id) {
            $comment->update(['delete' => 1]);
        }

        return back();
    }

    public function get_comments(Request $request) {
        $count = App\Comment::where('page_id', $request->page)->count();

        if(auth()->user() == NULL) {
            $auth = -1;
        }
        else {
            $auth = auth()->user()->id;
        }

        $get_comments = App\Comment::where('page_id', $request->page)->skip(5)->take($count-5)->get();
        $comments = App\Comment::CreateCommentsArray($get_comments, $auth);

        return response()->json($comments);
    }

    public function comments(Request $request) {
        $id = $request->id;
        $user = App\User::findOrFail($id);
        $comments = $user->Comments()->where('delete', '0')->get();

        return view('comments', compact('comments', 'user'));
    }

    public function users() {
        $users = App\User::all();

        return view('users', compact('users'));
    }
}
