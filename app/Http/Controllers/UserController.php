<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Validator;
use Hash;
use \App\User;
use \App\Duty;
use Illuminate\Support\Facades\Input;
use \Response;
use \App\Group;
use \Session;
use \App\Subject;
use \App\Mark;
use \Auth;

class UserController extends Controller
{
    public function postLogin()
    {
        $newToken = csrf_token();
        if (!Input::has('_token')) {
            return Response::json([
                'success' => false,
                'code'    => 'LOGIN_NO_TOKEN',
                'newToken'=> $newToken
            ]);
        }

        if (Session::token() !== Input::get('_token')) {
            return Response::json([
                'success' => false,
                'code'    => 'LOGIN_BAD_TOKEN',
                'newToken'=> $newToken
            ]);
        }

        $messages = array(
            'username.required' => 'Neįvestas prisijungimo vardas.',
            'password.required' => 'Neįvestas slaptažodis.',
            'username.max' => 'Per įlgas prisijungimo vardas.',
            'password.min' => 'Per trumpas slaptažodis.',
            'password.max' => 'Per ilgas slaptažodis.'
        );

        $validation = Validator::make(Input::all(), [
            'username' => 'required|max:255',
            'password' => 'required|min:6|max:20',
        ], $messages);

        if ($validation->fails()) {
            return Response::json([
                'success' => false,
                'code'    => 'LOGIN_FAIL',
                'errors'  => $validation->getMessageBag(),
                'newToken'=> csrf_token()
            ]);
        } else {
            $name = e(Input::get('username'));
            $password = e(Input::get('password'));
            $rememberMe = e(Input::get('remember')) == false ? false : true;

            $auth = \Auth::attempt(array(
              'loginName' => $name,
              'password' => $password
            ), $rememberMe);

            if ($auth) {
                \Auth::user()->updated_at = new \Datetime();
                \Auth::user()->save();
                return Response::json([
                    'success' => true,
                    'code'    => 'LOGIN_OK',
                    'messages' => ['Prijungta sėkmingai, prašome palaukti!'],
                ]);
            } else {
                return Response::json([
                    'success' => false,
                    'code'    => 'LOGIN_FAIL_AUTH',
                    'errors'  => ['Atsiprašome, bet neteisingi prisijungimo duomenys.'],
                    'newToken'=> csrf_token()
                ]);
            }
        }
    }

    public function getLogout()
    {
        \Auth::logout();
        return redirect()->route('home');
    }

    public function createNewUser(Request $request)
    {
        $messages = array(
          'name.required' => 'Neįvestas vardas.',
          'second_name.required' => 'Neįvesta pavardė.',
          'loginName.required' => 'Neįvestas prisijungimo vardas.',
          'password.required' => 'Neįvestas slaptažodis.',
          'pcode.required' => 'Neįvestas asmens kodas.',
          'status.required' => 'Neįvestas statusas.',
          'name.max' => 'Per ilgas vardas.',
          'second_name.max' => 'Per ilga pavardė.',
          'loginName.max' => 'Per ilgas prisijungimo vardas.',
          'password.max' => 'Per ilgas slaptažodis.',
          'pcode.max' => 'Per ilgas asmens kodas.',
          'password.min' => 'Per trumpas slaptažodis.',
          'name.min' => 'Per trumpas vardas.',
          'second_name.min' => 'Per trumpa pavardė.',
          'loginName.min' => 'Per trumpas prisijungimo vardas.',
          'pcode.min' => 'Per trumpas asmens kodas.',
          'status.min' => 'Neįvestas statusas.',
        );

        $validation = Validator::make($request->all(), [
          'name' => 'required|min:4|max:255',
          'second_name' => 'required|min:2|max:255',
          'loginName' => 'required|min:4|max:255|unique:users',
          'password' => 'required|min:6|max:255',
          'pcode' => 'required|min:11|max:12',
          'status' => 'required:min:1',
        ], $messages);


        $existingUser = User::where('pcode', e($request['pcode'])) -> first();
        if (!empty($existingUser)) {
            $validation->getMessageBag()->add('user', 'Atsiprašome bet toks asmens kodas jau egzistuoja.');
            return json_encode(array('err' => 'ExistingUsedr', 'messages' => $validation->messages()->getMessages()));
        }

        if ($validation->fails()) {
            return json_encode(array('err' => 'Fields', 'messages' => $validation->messages()->getMessages(), 'new_token' => csrf_token()));
        } else {
            User::create([
                'name' => e($request['name']),
                'second_name' => e($request['second_name']),
                'pcode' => e($request['pcode']),
                'password'  => \Hash::make(e($request['password'])),
                'loginName' => e($request['loginName']),
                'level' => e($request['status'])
            ]);


            return json_encode(array('err' => ''));
        }
    }

    public function generateNewLoginName(Request $request)
    {
        $messages = array(
          'name.required' => 'Neįvestas prisijungimo vardas.',
          'password.required' => 'Neįvestas slaptažodis.',
          'name.max' => 'Per įlgas prisijungimo vardas.',
          'password.min' => 'Per trumpas slaptažodis.',
          'password.max' => 'Per ilgas slaptažodis.'
        );

        $validation = Validator::make($request->all(), [
          'name' => 'required|max:255',
          'second_name' => 'required|min:2',
        ], $messages);

        if ($validation->fails()) {
            return json_encode(array('err'=>'data'));
        } else {
            $loginName = e($request['name']).'.'.substr(e($request['second_name']), 0, 2).mt_rand(1000, 9999);

            $existingUser = User::where('loginName', $loginName) -> first();

            $index = 0;
            while (!empty($existingUser)) {
                $loginName = e($request['name']).'.'.substr(e($request['second_name']), 0, 2).mt_rand(1000, 9999);
                $existingUser = User::where('loginName', $loginName) -> first();
                $index ++;

                if ($index >= 8999) {
                    break;
                    return json_encode(array('err' => 'Unique nr cant be found'));
                }
            }


            return json_encode(array('err' => '', 'loginName' => $loginName));
        }
    }

    public function showUsersList($json = null)
    {
        $duty = new Duty();
        $usersList = array();
        $dutiesList = $duty->where('id', '>', '0')->get();
        if ($json) {
            $user = new User();
            $usersList = $user->where('id', '>', '0')->get();
            $uList = array();
            foreach ($usersList as $user) {
                $uList['data'][] = array(
                    'id' => $user->id,
                    'loginName' => $user->loginName,
                    'fullName' => $user->name.' '.$user->second_name,
                    'pcode' => $user->pcode,
                    'action' => '<button type="button" class="btn btn-primary btn-lg" onClick="openEditModal(\''.$user->id.'\');" data-target="#editModal">
                         <i class="fa fa-pencil"></i>
                        </button>
                        <button type="button" class="btn btn-danger btn-lg"
                            onClick="openDeliteSwal(\''.$user->id.'\', \''.$user->loginName.'\', \''.$user->name.' '.$user->second_name.'\');">
                              <i class="fa fa-trash"></i>
                        </button>',
                );
            }
            return Response::json($uList);
        }

        return view('pages.listUsers', ['usersList' => $usersList, 'dutiesList' => $dutiesList]);
    }


    public function getDataAboutUser($userId)
    {
        $user = User::where('id', $userId)->first();

        $allInfo = array();
        $allInfo['userInfo'] = array(
            'id' => $user->id,
            'loginName' => $user->loginName,
            'name' => $user->name,
            'second_name' => $user->second_name,
            'pcode' => $user->pcode,
            'level' => $user->level,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at
            );

        $groupsInfo = $user->group()->get();
        $allInfo['groupsInfo'] = array();
        foreach ($groupsInfo as $group) {
            $allInfo['groupsInfo'][] = array(
                'id' => $group->id,
                'title' => $group->title
                );
        }

        $subjectsInfo = $user->subjects()->get();
        $allInfo['subjectsInfo'] = array();
        foreach ($subjectsInfo as $subj) {
            $allInfo['subjectsInfo'][] = array(
                'id' => $subj->id,
                'title' => $subj->title
                );
        }

        $dutiesInfo = $user->duties()->get();
        $allInfo['dutiesInfo'] = array();
        foreach ($dutiesInfo as $duty) {
            $permitsList = $duty->permits()->get();
            $permits = array();
            foreach ($permitsList as $permit) {
                $permits[] = array(
                    'id' => $permit->id,
                    'title' => $permit->title,
                    'code' => $permit->code
                    );
            }

            $allInfo['dutiesInfo'][] = array(
                'id' => $duty->id,
                'title' => $duty->title,
                'permits' => $permits
                );
        }


        $allInfo = (object) $allInfo;
        return Response::json([
            'success' => true,
            'allInfo' => $allInfo,
        ]);
    }










    public function newUserForm()
    {
        return view('pages.newUserForm');
    }

    public function generateNewPassword($maxLength = 5)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        $maxLength = $maxLength > 10 ? 10 : $maxLength;
        for ($i = 0; $i < $maxLength; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return Response::json([
            'success' => true,
            'code'    => 'PASS_RAND',
            'password' => $randomString
        ]);
    }









    public function updateUserData()
    {
        if (!Input::has('_token')) {
            return Response::json([
                'success' => false,
                'message' => 'Neįvestas tokenas, prašome perkrauti puslapį.'
            ]);
        }

        if (Session::token() !== Input::get('_token')) {
            return Response::json([
                'success' => false,
                'message' => 'Blogas tokenas, prašome perkrauti puslapį.'
            ]);
        }

        if (!Input::has('id')) {
            return Response::json([
                'success' => false,
                'message' => 'Nerastas vartotojas',
                'code' => 'UserNotFound'
            ]);
        }

        $messages = array(
          'name.required' => 'Neįvestas vardas.',
          'second_name.required' => 'Neįvesta pavardė.',
          'pcode.required' => 'Neįvestas asmens kodas.',
          'name.max' => 'Per ilgas vardas.',
          'second_name.max' => 'Per ilga pavardė.',
          'pcode.max' => 'Per ilgas asmens kodas.',
          'name.min' => 'Per trumpas vardas.',
          'second_name.min' => 'Per trumpa pavardė.',
          'pcode.min' => 'Per trumpas asmens kodas.',
        );

        $validation = Validator::make(Input::all(), [
          'name' => 'required|min:4|max:255',
          'second_name' => 'required|min:2|max:255',
          'pcode' => 'required|min:11|max:12',
        ], $messages);


        $existingUser = User::where('id', e(Input::get('id'))) -> first();
        if (empty($existingUser)) {
            return Response::json([
                'success' => false,
                'message' => 'Vartotojas nebuvo rastas.',
                'code'=>'UserNotFound'
            ]);
        }

        if ($validation->fails()) {
            return Response::json([
                'success' => false,
                'messages' => $validation->messages()->getMessages(),
                'code'=>'Fields'
            ]);
        } else {
            if (Input::has('level')) {
                $existingUser->level = e(Input::get('level'));
            }
            $existingUser->name = e(Input::get('name'));
            $existingUser->second_name = e(Input::get('second_name'));
            $existingUser->pcode = e(Input::get('pcode'));
            if (!empty(Input::get('password'))) {
                $existingUser->password = e\Hash::make(e(Input::get('password')));
            }
            $existingUser->save();

            $dutyId = (int) Input::get('dutyId');
            if ($dutyId != -1) {
                $duty = Duty::where('id', $dutyId)->first();

                if (count($existingUser->duties()->get()) < 1) {
                    $existingUser->duties()->attach($duty);
                } else {
                    $existingUserDuty = $existingUser->duties()->first();
                    if (!empty($existingUserDuty)) {
                        $existingUser->duties()->detach($existingUserDuty);
                    }

                    $existingUser->duties()->attach($duty);
                }
            } else {
                $existingUserDuty = $existingUser->duties()->first();
                if (!empty($existingUserDuty)) {
                    $existingUser->duties()->detach($existingUserDuty);
                }
            }


            $allInfo = array();
            $allInfo['userInfo'] = array(
                'id' => $existingUser->id,
                'loginName' => $existingUser->loginName,
                'name' => $existingUser->name,
                'second_name' => $existingUser->second_name,
                'pcode' => $existingUser->pcode,
                'level' => $existingUser->level,
                'created_at' => $existingUser->created_at,
                'updated_at' => $existingUser->updated_at
                );

            $groupsInfo = $existingUser->group()->get();
            foreach ($groupsInfo as $group) {
                $allInfo['groupsInfo'][] = array(
                    'id' => $group->id,
                    'title' => $group->title
                    );
            }

            $subjectsInfo = $existingUser->subjects()->get();
            foreach ($subjectsInfo as $subj) {
                $allInfo['subjectsInfo'][] = array(
                    'id' => $subj->id,
                    'title' => $subj->title
                    );
            }

            $dutiesInfo = $existingUser->duties()->get();
            foreach ($dutiesInfo as $duty) {
                $permitsList = $duty->permits()->get();
                $permits = array();
                foreach ($permitsList as $permit) {
                    $permits[] = array(
                        'id' => $permit->id,
                        'title' => $permit->title,
                        'code' => $permit->code
                        );
                }

                $allInfo['dutiesInfo'][] = array(
                    'id' => $duty->id,
                    'title' => $duty->title,
                    'permits' => $permits
                    );
            }


            $allInfo = (object) $allInfo;
            return Response::json([
                'success' => true,
                'message' => 'Success',
                'updatedInfo' => $allInfo
            ]);
        }
    }

    public function getUserInfo()
    {
        if (!Input::has('id')) {
            return Response::json([
                'success' => false,
                'message' => 'Nerastas vartotojas',
                'code' => 'UserNotFound'
            ]);
        }

        $existingUser = User::where('id', e(Input::get('id'))) -> first();
        if (empty($existingUser)) {
            return Response::json([
                'success' => false,
                'message' => 'Vartotojas nebuvo rastas.',
                'code'=>'UserNotFound'
            ]);
        }


        $allInfo = array();
        $allInfo['userInfo'] = array(
            'id' => $existingUser->id,
            'loginName' => $existingUser->loginName,
            'name' => $existingUser->name,
            'second_name' => $existingUser->second_name,
            'pcode' => $existingUser->pcode,
            'level' => $existingUser->level,
            'created_at' => $existingUser->created_at,
            'updated_at' => $existingUser->updated_at
            );

        $groupsInfo = $existingUser->group()->get();
        foreach ($groupsInfo as $group) {
            $allInfo['groupsInfo'][] = array(
                'id' => $group->id,
                'title' => $group->title
                );
        }

        $subjectsInfo = $existingUser->subjects()->get();
        foreach ($subjectsInfo as $subj) {
            $allInfo['subjectsInfo'][] = array(
                'id' => $subj->id,
                'title' => $subj->title
                );
        }

        $dutiesInfo = $existingUser->duties()->get();
        foreach ($dutiesInfo as $duty) {
            $permitsList = $duty->permits()->get();
            $permits = array();
            foreach ($permitsList as $permit) {
                $permits[] = array(
                    'id' => $permit->id,
                    'title' => $permit->title,
                    'code' => $permit->code
                    );
            }

            $allInfo['dutiesInfo'][] = array(
                'id' => $duty->id,
                'title' => $duty->title,
                'permits' => $permits
                );
        }


        $allInfo = (object) $allInfo;
        return Response::json([
            'success' => true,
            'message' => 'Success',
            'updatedInfo' => $allInfo
        ]);
    }




    public function getUsersListByKeyword()
    {
        if (!Input::has('keyword')) {
            return Response::json([
                'success' => false
            ]);
        }
        $keyword = e(Input::get('keyword'));
        $users = User::where('name', 'LIKE', '%'.$keyword.'%')->get();
        $usersList = array();
        foreach ($users as $user) {
            $existingUserDuty = $user->duties()->first();
            $usersList[] = array('id' => $user->id, 'name' => $user->name, 'second_name' => $user->second_name, 'loginName' => $user->loginName, 'dutyTitle' => $existingUserDuty->title);
        }
        return Response::json([
            'success' => true,
            'usersList' => $usersList
        ]);
    }

    public function home()
    {
        $usersCount = \DB::table('users')->count();
        $groupsCount = \DB::table('groups')->count();
        $subjectsCount = \DB::table('subjects')->count();
        $conversationsCount = \DB::table('users_conversations')->count();
        $messagesCount = \DB::table('user_messages')->count();

        return view('pages.home', [
            'usersCount' => $usersCount,
            'groupsCount' => $groupsCount,
            'subjectsCount' => $subjectsCount,
            'conversationsCount' => $conversationsCount,
            'messagesCount' => $messagesCount,
        ]);
    }

    public function dellUserComp()
    {
        if (!Input::has('userId')) {
            return Response::json([
                'success' => false,
                'message' => 'Prašome pasirinkti vartotoją!',
                'code'    => 'UserFieldEmpty'
            ]);
        }

        $userId = e(Input::get('userId'));

        $existingUser = User::where('id', $userId) -> first();
        if (empty($existingUser)) {
            return Response::json([
                'success' => false,
                'message' => 'Vartotojas nebuvo rastas, perkraukite puslapį!',
                'code'    => 'UserNotFound'
            ]);
        }


        $existingUser->delete();
        return Response::json([
            'success' => true,
            'message' => 'Vartotojas ištrintas.',
            'code'    => 'UserDeleteOk'
        ]);
    }


    public function showProfile($userId = null)
    {
        if ($userId != null) {
            $user = User::find($userId);
        } else {
            $user = Auth::user();
        }

        $photosList = $user->photos()->get();
        $userInfo = array();
        $userInfo['user'] = $user;
        foreach ($photosList as $photo) {
            if ($photo->profilePic) {
                $userInfo['profilePicture'] = $photo->photoPath;
            } else {
                $userInfo['otherPhotos'][] = $photo->photoPath;
            }
        }
        $userDefautls = array();
        $userDefaults['defProfilePicture'] = '/users/photos/default.jpg';

        $userInfo['duties'] = $user->duties()->get();
        $userInfo['groups'] = $user->group()->get();
        $userInfo['subjects'] = $user->subjects()->get();
        $userInfo['marks'] = $user->marks()->get();
        $userInfo['isMe'] = Auth::user()->loginName == $user->loginName ? true : false;




        $userInfo = (object) $userInfo;
        $userDefaults = (object) $userDefaults;
        return view('pages.userProfile', [
            'userInfo' => $userInfo,
            'defaults' => $userDefaults,
        ]);
    }


    public function updateUserInformation()
    {
        if (!Input::has('_token')) {
            return Response::json([
                'success' => false,
                'code'    => 'TOKEN_NOT_FOUND',
                'errors' => ['Neįvestas tokenas, prašome perkrauti puslapį.']
            ]);
        }

        if (Session::token() !== Input::get('_token')) {
            return Response::json([
                'success' => false,
                'code'    => 'TOKEN_BAD',
                'errors' => ['Blogas tokenas, prašome perkrauti puslapį.']
            ]);
        }

        $user = Auth::user();

        if (Input::has('password')) {
            $password = e(Input::get('password'));
            $user->password = \Hash::make($password);
        }

        $user->save();
        return Response::json([
            'success' => true,
            'messages' => ['Išsaugota sėkmingai!']
        ]);
    }
}
