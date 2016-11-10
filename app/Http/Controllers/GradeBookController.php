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

class GradeBookController extends Controller
{
    public function showMyMarks()
    {
        $user = \Auth::user();
        $userGroup = $user->group()->first();
        $userSubjectList = $userGroup->subjects()->get();

        $monthsOut = '';
        $array = array("Sausis", "Vasaris", "Kovas", "Balandis", "Gegužė",
            "Birželis", "Liepa", "Rugpjūtis", "Rugsėjis", "Spalis",
            "Lapkritis", "Gruodis");
        $year = date("Y");

        for ($m=0; $m<12; $m++) {
            $daysCount = cal_days_in_month(CAL_GREGORIAN, $m+1, $year);
            $monthsOut .= '<th class="borderBoth" colspan="'.$daysCount.'">'.$array[$m].'</th>';
        }

        return view('pages.gradeBook', ['userSubjectList' => $userSubjectList,
            'monthsOut' => $monthsOut]);
    }

    public function showUserGrades()
    {
        if(!Input::has('user')) {
            return view('pages.home');
        }
        $userId = e(Input::get('user'));
        $user = User::where('id', $userId)->first();
        $userGroup = $user->group()->first();
        $userSubjectList = $userGroup->subjects()->get();

        $monthsOut = '';
        $array = array("Sausis", "Vasaris", "Kovas", "Balandis", "Gegužė",
            "Birželis", "Liepa", "Rugpjūtis", "Rugsėjis", "Spalis",
            "Lapkritis", "Gruodis");
        $year = date("Y");

        for ($m=0; $m<12; $m++) {
            $daysCount = cal_days_in_month(CAL_GREGORIAN, $m+1, $year);
            $monthsOut .= '<th class="borderBoth" colspan="'.$daysCount.'">'.$array[$m].'</th>';
        }

        return view('pages.gradeBook', ['userSubjectList' => $userSubjectList,
            'monthsOut' => $monthsOut, 'userInfo' => $user]);
    }
}
