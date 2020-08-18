<?php

namespace App\Http\Middleware;

use Closure;
use App;

class CheckBookAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {

        $book = App\Book::findOrFail($request->id);

        $access = App\BookAccess::where('book_id', $request->id)->first();

        if(auth()->user() == null) {
            if($access == null)
                return back();

            else
                return $next($request);
        }
        else {
            if($book->user_id != auth()->user()->id && $access == null) {
                $lib_access = App\LibAccess::where([['lib_id', $book->user_id], ['user_id', auth()->user()->id]])->first();

                if($lib_access == null)
                    return back();
            }
        }

        return $next($request);
    }
}
