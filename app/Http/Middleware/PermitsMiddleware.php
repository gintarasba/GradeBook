<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

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

        switch ($routeName) {
            case 'showUserGrades':
            case 'showUsersGrades':
                $user = Auth::user();
                $duties = $user->duties()->get();
                foreach ($duties as $duty) {
                    $permit = $duty->permits()->where('code', 'OBJECT_MARKS_VIEW')->get();
                    if (!empty($permit)) {
                        $valid = true;
                    }
                }
                $params = $request->route()->parameters();
                //\App\FancyLib\Functions::pr($params);

                if (isset($params['group'])) {
                    $params['group'] = (int) $params['group'];
                    $userGroups = $user->group()->where('group_id', $params['group'])->first();
                    if (is_null($userGroups)) {
                        $valid = false;
                    }
                }

                if (isset($params['user'])) {
                    $params['user'] = (int) $params['user'];
                    $userInfo = \App\User::where('id', $params['user'])->first();
                    if (is_null($userInfo)) {
                        $valid = false;
                    } else {
                        $groupsList = $userInfo->group()->get();
                        $myGroups = $user->group()->get();
                        $valid = false;
                        foreach ($myGroups as $myGroup) {
                            foreach ($groupsList as $userGroup) {
                                if ($myGroup->id == $userGroup->id) {
                                    $valid = true;
                                    break;
                                }
                            }
                        }
                    }
                }

            break;

            case 'showMyMarks':
                $user = Auth::user();
                $duties = $user->duties()->get();
                foreach ($duties as $duty) {
                    $permit = $duty->permits()->where('code', 'USER_MARKS_VIEW')->get();
                    if (!empty($permit)) {
                        $valid = true;
                    }
                }
            break;

            case 'updateGrade':
                $user = Auth::user();
                $duties = $user->duties()->get();
                foreach ($duties as $duty) {
                    $permit = $duty->permits()->where('code', 'OBJECT_MARKS_EDIT')->get();
                    if (!empty($permit)) {
                        $valid = true;
                    }
                }
        }

        if ($valid == false) {
            return redirect('/');
        }

        return $next($request);
    }
}
