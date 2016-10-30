<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class PermitsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //http://localhost:8000/user/gradeBook
        $routeName = $request->route()->getName();
        $valid = false;
        switch($routeName) {
            case 'showUserMarks':
                $user = Auth::user();
                $duties = $user->duties()->get();
                foreach($duties AS $duty) {
                    $permit = $duty->permits()->where('code' , 'USER_MARKS_VIEW')->get();
                    if(!empty($permit)) $valid = true;
                }
            break;
        }

        if ($valid == false) {
            return redirect('/');
        }

        return $next($request);
    }
}
