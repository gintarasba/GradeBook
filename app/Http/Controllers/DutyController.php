<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Validator;
use Hash;
use \App\User;
use \App\Duty;


class DutyController extends Controller
{
    public function createNewDuty(Request $request)
    {
        if(!isset($request['dutyTitle']))
            return json_encode(array('err' => 'empty'));

        $request['dutyTitle'] = e($request['dutyTitle']);

        $newDuty = new Duty();
        $newDuty->title = $request['dutyTitle'];
        $newDuty->save();
        return json_encode(array('err' => ''));
    }

    public function updateDuty(Request $request)
    {
        if(!isset($request['dutyId']) OR !isset($request['dutyTitle']))
            return json_encode(array('err' => 'empty'));

        $request['dutyId'] = (int) $request['dutyId'];
        $dutyEdit = Duty::where('id' , '=' , $request['dutyId'])->first();
        if($dutyEdit == null)
            return json_encode(array('err' => 'empty'));

        $dutyEdit->title = e($request['dutyTitle']);
        $dutyEdit->save();
        return json_encode(array('err' => ''));
    }

    public function dutiesManagingForm()
    {
        $duty = new \App\Duty;
        $dutiesList = $duty->get();
        return view('pages.duty',[
                'dutiesList' => $dutiesList
            ]);
    }


    public function detachDuty(Request $request)
    {
        if(!isset($request['dutyId']) || !isset($request['userid']))
            return json_encode(array('err' => 'Empty values!', 'code' => 'VALUES_EMPTY'));

        $id = (int) $request['userid'];
        $dutyId = (int) $request['dutyId'];

        $existingUser = User::where('id', $id) -> first();
        $duty = Duty::where('id', $dutyId)->first();
        if(empty($duty) || empty($existingUser))
            return json_encode(array('err' => 'Bad values!', 'code' => 'VALUES_BAD'));

        $existingUser->duties()->detach($duty);
        return json_encode(array('err' => '', 'code' => '1'));
    }
}
