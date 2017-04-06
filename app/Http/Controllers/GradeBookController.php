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

class GradeBookController extends Controller
{
    public function showMyMarks()
    {
        $user = Auth::user();

        return view('pages.gradeBook', [
            'userInfo' => $user
        ]);
    }

    public function showUserGrades($userId)
    {
        $user = User::where('id', $userId)->first();
        return view('pages.gradeBook', [
            'userInfo' => $user
        ]);
    }

    public function updateGrade()
    {
        if (!Input::has('_token')) {
            return Response::json(['success' => false, 'message' => 'Prašome perkrauti puslapį.']);
        }

        if (Session::token() !== e(Input::get('_token'))) {
            return Response::json(['success' => false, 'message' => 'Blogas tokenas, prašome perkrauti puslapį.']);
        }

        if (!Input::has('user')) {
            return Response::json(['success' => false, 'message' => 'Prašome įvesti vartotojo id.']);
        }

        if (!Input::has('subj')) {
            return Response::json(['success' => false, 'message' => 'Prašome įvesti pamokos/dalyko id.']);
        }

        if (!Input::has('dat')) {
            return Response::json(['success' => false, 'message' => 'Prašome įvesti data id.']);
        }

        if (!Input::has('mark')) {
            return Response::json(['success' => false, 'message' => 'Prašome įvesti pažimį.']);
        }

        $userId = e(Input::get('user'));
        $subjectId = e(Input::get('subj'));
        $data = e(Input::get('dat'));
        $markVal = (int) e(Input::get('mark'));

        // patikrini ar egzistuoja useris/ subject/ data

        $mark = Mark::where([
            ['user_id', $userId],
            ['subject_id', $subjectId],
            ['day', $data],
        ])->first();

        if (!empty($mark)) {
            $mark->mark = $markVal;
        } else {
            $mark = new Mark();
            $mark->user_id = $userId;
            $mark->subject_id = $subjectId;
            $mark->mark = $markVal;
            $mark->day = $data;
        }
        $mark->save();

        if ($markVal == -1) {
            $mark->delete();
        } else {
            $mark->touch();
        }


        return Response::json(['success' => true]);
    }

    public function getMarksJson()
    {
        if (!Input::has('userId')) {
            return Response::json([
                'success' => false
            ]);
        }

        $user = User::where('id', $userId)->first();
        $userGrades = Mark::where('user_id', $userId)->get();

        $gradesList = array();
        foreach ($userGrades as $grade) {
            $data = explode('-', $grade->day);
            $gradesList[$grade->subject_id][$data[0]][$data[1]][$data[2]] = $grade->mark;
        }

        return Response::json([
            'success' => true,
            'grades' => $gradesList,
        ]);
    }


    public function showUsersGrades($group)
    {
        $currentGroup = Group::find($group);
        if (is_null($currentGroup)) {
            return redirect()->back();
        }

        return view('pages.multipleUsersGradeBook', [
            'currentGroup' => $currentGroup
        ]);
    }
}
