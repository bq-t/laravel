<?php

namespace App\Http\Controllers;

use App;
use Illuminate\Http\Request;

class LibController extends Controller {

	public function __construct() {
		$this->middleware('auth');
		$this->middleware('lib_access')->except(['share', 'take_access']);;
	}
    
	public function index(Request $request) {

		$id = $request->route('id');
		$user = App\User::findOrFail($id);

		$books = $user->Books;

		return view('lib', compact('books'));
	}

	public function share(Request $request) {

		$access = new App\LibAccess;

		$access->lib_id = auth()->user()->id;
		$access->user_id = $request->id;

		$access->save();

		return back();
	}

	public function take_access(Request $request) {
		$id = auth()->user()->id;

		$access = App\LibAccess::where([['lib_id', $id], ['user_id', $request->id]])->first();
		$access->delete();

		return back();
	}
}
