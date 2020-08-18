<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;

class BookController extends Controller {

	public function __construct() {
		$this->middleware('book_access')->except('create');
	}

	public function index(Request $request) {
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

	public function open(Request $request) {
		$book = $this->GetBookById($request->id);

		if($book->user_id == auth()->user()->id) {

			$access = new App\BookAccess;

			$access->book_id = $book->id;
			$access->save();
		}

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
