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
        App\User::findOrFail($id);
        $reply = [];
        $user = App\User::find($id);

        if($request->has('submit')) {
            $comment = new App\Comment;

            $comment->page_id = $id;
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
        }

        elseif($request->has('delcom')) {
            $user_id = auth()->user()->id;
            $comment = App\Comment::where('id', $request->com);

            if($comment->first()->user_id == $user_id || $id == $user_id) {
                $comment->update(['delete' => 1]);
            }
        }

        elseif($request->has('replycom')) {
            $r_id = $request->com;
            $r_com = App\Comment::where('id', $r_id)->first();
            $r_user = App\User::find($r_com->user_id);

            $reply['id'] = $r_id;
            $reply['user'] = $r_user->name;
        }

        $get_comments = App\Comment::where('page_id', $id)->take(5)->orderBy('created_at')->get();
        $comments = App\Comment::CreateCommentsArray($get_comments);

        return view('profile', compact('user', 'comments', 'reply'));
    }




    public function get_comments(Request $request) {
        $count = App\Comment::where('page_id', $request->page)->count();

        $get_comments = App\Comment::where('page_id', $request->page)->skip(5)->take($count-5)->get();
        $comments = App\Comment::CreateCommentsArray($get_comments);

        return response()->json($comments);
    }

    public function comments(Request $request) {
        $id = $request->id;
        App\User::findOrFail($id);
        $user = App\User::find($id);
        $comments = $user->Comments()->where('delete', '0')->get();

        return view('comments', compact('comments', 'user'));
    }
}
