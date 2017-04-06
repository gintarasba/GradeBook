<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use \Response;
use \App\User;
use \App\UserPhoto;
use Illuminate\Support\Facades\Storage;

class RasberryApiController extends Controller
{
    public function checkStatus($appId)
    {
        return Response::json([
            'success' => true
        ]);
    }


    public function userCheckpointEvent()
    {
        if (!Input::has('appId')) {
            return Response::json([
                'success' => false,
                'error'   => 'No appId specified'
            ]);
        }
        $appId = Input::get('appId');

        if ($appId != '6874264684643642863') {
            return Response::json([
                'success' => false,
                'error'   => 'Incorect appId'
            ]);
        }

        if (!Input::has('uid')) {
            return Response::json([
                'success' => false,
                'error'   => 'No uid specified'
            ]);
        }
        if (!Input::file('file')->isValid()) {
            return Response::json([
                'success' => false,
                'error'   => 'No photo specified'
            ]);
        }

        $uid = Input::get('uid');
        $existingUser = User::where('uid', $uid)->first();

        if (is_null($existingUser)) {
            return Response::json([
                'success' => false,
                'error' => 'User does not exist!',
            ]);
        }
        $counter = 0;
        while (file_exists('users/photos/'.$existingUser->loginName.'-'.$counter.'.jpg')) {
            $counter ++;
        }
        if ($counter >= 10) {
            for ($i = 0; $i <= 10; $i ++) {
                if (file_exists('users/photos/'.$existingUser->loginName.'-'.$i.'.jpg')) {
                    unlink('users/photos/'.$existingUser->loginName.'-'.$i.'.jpg');
                }
            }
        }
        Input::file('file')->move('users/photos', $existingUser->loginName.'-'.$counter.'.jpg');

        $userPhoto = new UserPhoto;
        $userPhoto->user_id = $existingUser->id;
        $userPhoto->profilePic = 0;
        $userPhoto->piPhoto = 1;
        $userPhoto->photoPath = '/users/photos/'.$existingUser->loginName.'-'.$counter.'.jpg';
        $userPhoto->save();

        return Response::json([
            'success' => true,
            'message' => 'OK',
            'existingUser' => $existingUser,
            'Input' => Input::all()
        ]);
    }
}
