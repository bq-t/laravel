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

	public function book(Request $request) {
		$book = $this->GetBookById($request->id);

		return view('book', compact('book'));
	}

	public function create(Request $request) {

		$book = new App\Book;

		$book->user_id = auth()->user()->id;
		$book->name = $request->name;
		$book->text = $request->text;

		$book->save();

		return back();
	}

	public function edit(Request $request) {
		$book = $this->GetBookById($request->id);

		if($request->has('submit')) {

			if($book->user_id == auth()->user()->id) {
				$book->name = $request->name;
				$book->text = $request->text;
				$book->save();
			}

			return redirect()->route('book', ['id' => $request->id]);
		}

		return view('book_edit', compact('book'));
	}

	public function delete(Request $request) {
		$book = $this->GetBookById($request->id);

		if($book->user_id == auth()->user()->id) {
			$book->delete();
		}

		return back();
	}


	public function GetBookById($id) {
		$book = App\Book::findOrFail($id);
		return $book;
	}
}
