<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Validator;
use Hash;
use \App\User;
use \App\Duty;

class UserController extends Controller
{
    //
    public function postLogin(Request $request)
    {
      $messages = array(
        'name.required' => 'Neįvestas prisijungimo vardas.',
        'password.required' => 'Neįvestas slaptažodis.',
        'name.max' => 'Per įlgas prisijungimo vardas.',
        'password.min' => 'Per trumpas slaptažodis.',
        'password.max' => 'Per ilgas slaptažodis.'
      );

      $validation = Validator::make($request->all(),[
        'name' => 'required|max:255',
        'password' => 'required|min:6|max:20',
      ], $messages);

      if($validation->fails()) {
          return redirect()->back()->with('new_token', csrf_token())->withErrors($validation, 'pLogin');
      } else {

          $auth = \Auth::attempt( array(
              'loginName' => e($request['name']),
              'password' => e($request['password'])
          ), isset($request['rememberMe']) ? true : false);

          if($auth) {
              \Auth::user()->updated_at = new \Datetime();
              \Auth::user()->save();
              return redirect()->back();
          } else {
              $validation->getMessageBag()->add('user', 'Atsiprašome, bet neteisingi prisijungimo duomenys.');
              return redirect()->back()->withInput()->with('new_token', csrf_token())->withErrors($validation, 'pLogin');
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

        $validation = Validator::make($request->all(),[
          'name' => 'required|min:4|max:255',
          'second_name' => 'required|min:2|max:255',
          'loginName' => 'required|min:4|max:255|unique:users',
          'password' => 'required|min:6|max:255',
          'pcode' => 'required|min:11|max:12',
          'status' => 'required:min:1',
        ], $messages);


        $existingUser = User::where('pcode', e($request['pcode'])) -> first();
        if(!empty($existingUser)) {
            $validation->getMessageBag()->add('user', 'Atsiprašome bet toks asmens kodas jau egzistuoja.');
            return json_encode(array('err' => 'ExistingUsedr', 'messages' => $validation->messages()->getMessages()));
        }

        if($validation->fails()) {
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

        $validation = Validator::make($request->all(),[
          'name' => 'required|max:255',
          'second_name' => 'required|min:2',
        ], $messages);

        if($validation->fails()) {
            return json_encode(array('err'=>'data'));
        } else {

            $loginName = e($request['name']).'.'.substr(e($request['second_name']),0,2).mt_rand(1000,9999);

            $existingUser = User::where('loginName', $loginName) -> first();

            $index = 0;
            while(!empty($existingUser)) {
                $loginName = e($request['name']).'.'.substr(e($request['second_name']),0,2).mt_rand(1000,9999);
                $existingUser = User::where('loginName', $loginName) -> first();
                $index ++;

                if($index >= 8999) {
                    break;
                    return json_encode(array('err' => 'Unique nr cant be found'));
                }
            }


            return json_encode(array('err' => '', 'loginName' => $loginName));
        }
    }

    public function showUsersList($json = null)
    {
        $user = new User();
        $duty = new Duty();
        $usersList = $user->where('id', '>' , '0')->get();
        $dutiesList = $duty->where('id', '>' , '0')->get();
        if($json)
            return json_encode(array('usersList' => $usersList));

        return view('pages.listUsers', ['usersList' => $usersList, 'dutiesList' => $dutiesList]);
    }













    public function newUserForm()
    {
        return view('pages.newUserForm');
    }

    public function generateNewPassword(Request $request)
    {
        $maxLength = 5;
        if(isset($request['length'])) {
            $maxLength = (int) e($request['length']);
        }
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $maxLength; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return json_encode(array('err'=>'', 'password' => $randomString));
    }









    public function updateUserData(Request $request)
    {
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

        $validation = Validator::make($request->all(),[
          'name' => 'required|min:4|max:255',
          'second_name' => 'required|min:2|max:255',
          'pcode' => 'required|min:11|max:12',
        ], $messages);


        $existingUser = User::where('id', e($request['id'])) -> first();
        if(empty($existingUser)) {
            return json_encode(array('err' => 'UserNotFound'));
        }

        if($validation->fails()) {
            return json_encode(array('err' => 'Fields', 'messages' => $validation->messages()->getMessages()));
        } else {


            $existingUser->name = e($request['name']);
            $existingUser->second_name = e($request['second_name']);
            $existingUser->pcode = e($request['pcode']);
            if(!empty($request['password']))
                $existingUser->password = e\Hash::make(e($request['password']));
            $existingUser->save();

            $dutyId = (int) $request['dutyId'];
            if($dutyId != -1) {
                $duty = Duty::where('id', $dutyId)->first();
                if(!empty($duty) & !$existingUser->duties()->where('duties.id', $dutyId)->exists()) {
                    $existingUser->duties()->attach($duty);
                }
            }
            return json_encode(array('err' => ''));
        }
    }




    public function home()
    {

        return view('pages.home');
    }




}
