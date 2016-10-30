<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Validator;
use Hash;
use \App\User;
use \App\Duty;

class GroupController extends Controller
{
    public function newGroupsForm()
    {
        return view('pages.groups');
    }
}
