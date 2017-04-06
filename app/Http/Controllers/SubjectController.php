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

        if(!Input::has('title'))
         {
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

    public function attachSubjectToUser()
    {
        if(!Input::has('user_id') || !Input::has('subj_id') )             {
        return Response::json(['success' => false, 'message' => 'Neįvestas vartotojas arba pamoka/dalykas.']);
        }
        $user_id = (int) Input::get('user_id');
        $subject_id = (int) Input::get('subj_id');

        $existingUser = User::where('id', $user_id) -> first();
        $existingSubject = Subject::where('id', $subject_id) -> first();

        if($existingUser->subjects->contains($existingSubject)) {
            return Response::json(['success' => false, 'message' => 'Šis dalykas jau pridėtas, jai nerodo: perkraukite puslapį!', 'code' => '2UP']);
        }
        $existingUser->subjects()->attach($existingSubject);
        return Response::json(['success' => true, 'message' => 'Puiku..', 'subject' => array('id' => $existingSubject->id, 'title' => $existingSubject->title)]);
    }

    public function detachFromSbj()
    {
        if(!Input::has('userId')) {return Response::json(['success' => false, 'message' => 'Neįvestas vartotojo vardas.']);
            // bet cia ne linterio lyg :D
    }

        if(!Input::has('subjectId')) {
            return Response::json(['success' => false, 'message' => 'Neįvestas pamokos/dalyko id.']);
        }
        $userId =(int) e(Input::get('userId'));
        $subjectId = (int) e(Input::get('subjectId'));

        $existingUser = User::where('id', $userId)->first();
        $subject = Subject::where('id', $subjectId)->first();

        if(!$existingUser->subjects->contains($subject)) {
            return Response::json(['success' => false, 'message' => 'Šios grupės nėra!', 'code' => '2DOWN']);
        }

        $existingUser->subjects()->detach($subject);
        return Response::json(['success' => true]);
    }
}
