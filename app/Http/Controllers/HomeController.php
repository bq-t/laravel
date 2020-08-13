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

        elseif($request->has('delcom')) {
            $comment = App\Comment::where('id', $request->com)->update(['delete' => 1]);
        }

        $comments = [];

        $get_comments = App\Comment::where('page_id', $id)->take(5)->orderBy('created_at')->get();

        foreach ($get_comments as $comment) {
            $username = App\User::find($comment->user_id);

            array_push($comments, array(
                'id' => $comment->id,
                'user_id' => $comment->user_id, 
                'username' => $username->name, 
                'created_at' => $comment->created_at, 
                'title' => $comment->title, 
                'theme' => $comment->theme, 
                'text' => $comment->text, 
                'page_id' => $comment->page_id,
                'delete' => $comment->delete
            ));
        }

        return view('profile', compact('user', 'comments'));
    }

    public function comments(Request $request) {
        $count = App\Comment::where('page_id', $request->page)->count();

        $comments = App\Comment::where('page_id', $request->page)->skip(5)->take($count-5)->get();

        $data = [];
        foreach ($comments as $comment) {
            $username = App\User::find($comment->user_id);

            array_push($data, array(
                'id' => $comment->id,
                'user_id' => $comment->user_id, 
                'username' => $username->name, 
                'created_at' => $comment->created_at, 
                'title' => $comment->title, 
                'theme' => $comment->theme, 
                'text' => $comment->text, 
                'page_id' => $comment->page_id,
                'delete' => $comment->delete,
                'auth' => auth()->user()->id
            ));
        }

        return response()->json($data);
    }
}
