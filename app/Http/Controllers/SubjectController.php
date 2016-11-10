<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Hash;
use \App\User;
use \App\Duty;
use Illuminate\Support\Facades\Input;
use \Response;
use \App\Group;
use \Session;
use \App\Subject;

class SubjectController extends Controller
{

    public function newSubjectTitle()
    {
        if(!Input::has('_token')) {
            return Response::json(['success' => false, 'message' => 'Neįvestas tokenas, prašome perkrauti puslapį.']);
        }

        if(Session::token() !== Input::get('_token')) {
            return Response::json(['success' => false, 'message' => 'Blogas tokenas, prašome perkrauti puslapį.']);
        }
        
        if(!Input::has('title')) {
            return Response::json(['success' => false, 'message' => 'Neįvestas pavadinimas.']);
        }



        $filteredTitle = e(Input::get('title'));

        $subject = new Subject();
        $subject->title = $filteredTitle;
        $subject->save();
        return Response::json(['success' => true]);

    }


    public function newSubjectForm()
    {
        $subjectsList = Subject::all();

        return view('pages.subjects', ['subjectsList' => $subjectsList]);
    }
}
