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
        $this->middleware('auth');
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

        $user = App\User::find($id);

        if($request->has('submit')) {
            $comment = new App\Comment;

            $comment->page_id = $id;
            $comment->user_id = auth()->user()->id;
            $comment->title = $request->title;
            $comment->theme = $request->theme;
            $comment->text = $request->text;

            $comment->save();
        }

        $comments = [];

        $get_comments = App\Comment::where('page_id', $id)->orderBy('created_at')->take(5)->get();

        foreach ($get_comments as $comment) {
            $username = App\User::find($comment->user_id);

            array_push($comments, array('username' => $username->name, 'created_at' => $comment->created_at, 'title' => $comment->title, 'theme' => $comment->theme, 'text' => $comment->text));
        }

        return view('profile', compact('user', 'comments', 'comment_users'));
    }
}
