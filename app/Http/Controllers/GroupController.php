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

class GroupController extends Controller
{

    public function newGroupTitle()
    {
        if(!Input::has('_token')) {
            return Response::json(['success' => false, 'message' => 'Neįvestas tokenas, prašome perkrauti puslapį.']);
        }

        if(!Input::has('title')) {
            return Response::json(['success' => false, 'message' => 'Neįvestas pavadinimas.']);
        }

        if(Session::token() !== Input::get('_token')) {
            return Response::json(['success' => false, 'message' => 'Blogas tokenas, prašome perkrauti puslapį.']);
        }

        $filteredTitle = e(Input::get('title'));

        $group = new Group();
        $group->title = $filteredTitle;
        $group->save();
        return Response::json(['success' => true]);

    }

    public function addUserToGroup()
    {
        if(!Input::has('userId')) {
            return Response::json(['success' => false, 'message' => 'Neįvestas vartotojo vardas.']);
        }

        if(!Input::has('groupId')) {
            return Response::json(['success' => false, 'message' => 'Neįvestas grupės id.']);
        }
        $userId = e(Input::get('userId'));
        $groupId = e(Input::get('groupId'));

        $user = User::where('id', $userId)->first();
        $group = Group::where('id', $groupId)->first();

        if(!empty($user) & !empty($group)){
            if(empty($group->users()->find($userId))) {
                $user->group()->attach($group);
            } else {
                return Response::json(['success' => false]);
            }
        }

        $dutyTitle = '';
        $duty = $user->duties()->first();
        if(!empty($duty)) {
            $dutyTitle = $duty->title;
        }
        return Response::json(['success' => true, 'user' => array('id' => $user->id, 'name' => $user->name, 'dutyTitle' => $dutyTitle)]);
    }

    public function detachFromGroup()
    {
        if(!Input::has('userId')) {
            return Response::json(['success' => false, 'message' => 'Neįvestas vartotojo vardas.']);
        }

        if(!Input::has('groupId')) {
            return Response::json(['success' => false, 'message' => 'Neįvestas grupės id.']);
        }
        $userId = e(Input::get('userId'));
        $groupId = e(Input::get('groupId'));

        $user = User::where('id', $userId)->first();
        $group = Group::where('id', $groupId)->first();

        if(!empty($user) & !empty($group)){
            if(!empty($group->users()->find($userId)))
                $user->group()->detach($group);
        }

        return Response::json(['success' => true]);
    }

    public function newGroupsForm()
    {

        $groupsList = Group::all();

        return view('pages.groups', ['groupsList' => $groupsList]);
    }
}
