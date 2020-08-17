<?php

namespace App\Http\Controllers;

use App;
use Illuminate\Http\Request;

class LibController extends Controller {
    
	public function index(Request $request) {

		$id = $request->route('id');
		$user = App\User::findOrFail($id);

		$books = $user->Books;

		return view('lib', compact('books'));
	}

	/*public function form(Request $request) {
		

		return redirect()->route('library', ['id' => 1]);
	}*/

	public function create(Request $request) {

		$book = new App\Book;

		$book->user_id = auth()->user()->id;
		$book->name = $request->name;
		$book->text = $request->text;

		$book->save();

		return back();
	}

}
