<?php

namespace App\Http\Middleware;

use Closure;
use App;

class CheckLibAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        $id = auth()->user()->id;

        if($request->id != $id) {
            $access = App\LibAccess::where([['lib_id', $request->id], ['user_id', $id]])->first();

            if($access == null)
                return back();
        }

        return $next($request);
    }
}
