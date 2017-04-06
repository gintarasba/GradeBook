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
use \App\Permit;

class DutyController extends Controller
{
    public function createNewDuty(Request $request)
    {
        if (!isset($request['dutyTitle'])) {
            return json_encode(array('err' => 'empty'));
        }

        $request['dutyTitle'] = e($request['dutyTitle']);

        $newDuty = new Duty();
        $newDuty->title = $request['dutyTitle'];
        $newDuty->save();
        return json_encode(array('err' => ''));
    }

    public function updateDuty()
    {
        if (!Input::has('dutyId')) {
            return Response::json([
                'success' => false,
                'message' => 'Nepasirinkta pareiga!',
                'code'    => 'ED'
            ]);
        }

        if (!Input::has('dutyTitle')) {
            return Response::json([
                'success' => false,
                'message' => 'Neįvestas pavadinimas!',
                'code'    => 'ET'
            ]);
        }




        $dutyId = (int) e(Input::get('dutyId'));
        $dutyTitle = e(Input::get('dutyTitle'));

        if (strlen($dutyTitle) < 5) {
            return Response::json([
                'success' => false,
                'message' => 'Per trumpas pavadinimas!',
                'code'    => 'LT'
            ]);
        }
        $dutyEdit = Duty::where('id', $dutyId)->first();
        if (empty($dutyEdit)) {
            return Response::json([
                'success' => false,
                'message' => 'Tokios pareigos nėra!',
                'code'    => '!EP'
            ]);
        }

        $dutyEdit->title = $dutyTitle;
        $dutyEdit->save();
        $duty = Duty::where('id', $dutyId)->first();

        if (Input::has('permits')) {
            $permitsList  = e(Input::get('permits'));

            $permitsList = explode(',', $permitsList);
            $duty->permits()->detach();
            foreach ($permitsList as $permit) {
                $premit = (int) $permit;
                $permit = Permit::where('id', $permit)->first();

                if (!empty($permit)) {
                    $duty->permits()->attach($permit);
                }
            }
        }




        $allInfo = array();
        $allInfo['dutyInfo'] = $duty;

        foreach (Permit::all() as $permit) {
            $allInfo['permitsList'][$permit->code] = array(
                'id' => $permit->id,
                'title' => $permit->title,
                'code' => $permit->code,
                'comment' => $permit->comment,
                'checked' => false,
            );
        }

        foreach ($duty->permits()->get() as $permit) {
            $allInfo['permitsList'][$permit->code] = array(
                'id' => $permit->id,
                'title' => $permit->title,
                'code' => $permit->code,
                'comment' => $permit->comment,
                'checked' => true,
            );
        }


        return Response::json([
            'success' => true,
            'message' => 'Sėkmingai atnaujinta!',
            'code'    => '',
            'allInfo' => $allInfo,
        ]);
    }

    public function dutiesManagingForm()
    {
        $duty = new \App\Duty;
        $dutiesList = $duty->get();
        return view('pages.duty', [
                'dutiesList' => $dutiesList
            ]);
    }


    public function detachDuty(Request $request)
    {
        if (!isset($request['dutyId']) || !isset($request['userid'])) {
            return json_encode(array('err' => 'Empty values!', 'code' => 'VALUES_EMPTY'));
        }

        $id = (int) $request['userid'];
        $dutyId = (int) $request['dutyId'];

        $existingUser = User::where('id', $id) -> first();
        $duty = Duty::where('id', $dutyId)->first();
        if (empty($duty) || empty($existingUser)) {
            return json_encode(array('err' => 'Bad values!', 'code' => 'VALUES_BAD'));
        }

        $existingUser->duties()->detach($duty);
        return json_encode(array('err' => '', 'code' => '1'));
    }
}
