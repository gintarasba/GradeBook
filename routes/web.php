<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', [
    'uses' => 'UserController@home',
    'as' => 'home'
]);




Route::group(['middleware' => [
    'App\Http\Middleware\AuthCheckMiddleware',
    'App\Http\Middleware\PermitsMiddleware'
    ]], function() {
    Route::get('/user/gradeBook', [
        'uses' => 'GradeBookController@showUserMarks',
        'as' => 'showUserMarks'
    ]);
});






Route::group(['prefix' => 'admin', 'middleware' => 'App\Http\Middleware\AdminMiddleware'], function() {

    /**
     * UserController
     *
    */
    Route::get('/user/create', [
        'uses' => 'UserController@createNewUser',
        'as' => 'pNewUser'
    ]);

    Route::get('/user/update', [
        'uses' => 'UserController@updateUserData',
        'as' => 'updateUserData'
    ]);

    Route::get('/user/list/{json?}', [
        'uses' => 'UserController@showUsersList',
        'as' => 'showUsersList'
    ]);

    Route::get('/user/newPass', [
        'uses' => 'UserController@generateNewPassword',
        'as' =>'generateNewPassword'
    ]);

    Route::get('/user/newLogin', [
        'uses' => 'UserController@generateNewLoginName',
        'as' =>'generateNewLoginName'
    ]);

    /**
     * DutyController
     *
    */
    Route::get('/duty/newDuty', [
         'uses' => 'DutyController@createNewDuty',
         'as' => 'createNewDuty'
    ]);

    Route::get('/duty/updateDuty', [
        'uses' => 'DutyController@updateDuty',
        'as' => 'updateDuty'
    ]);

    Route::get('/duty/form', [
        'uses' => 'DutyController@dutiesManagingForm',
        'as' => 'dutiesManagingForm'
    ]);

    Route::get('/user/duty/detach', [
        'uses' => 'DutyController@detachDuty',
        'as' => 'detachDuty'
    ]);

    /**
     * GroupController
     *
    */

    Route::get('/user/groups', [
        'uses' => 'GroupController@newGroupsForm',
        'as' => 'newGroupsForm'
    ]);


    /**
     * MarksController
     *
    */




});



Route::group(['prefix' => 'auth'], function () {
    Route::post('/pLogin', [
      'uses' => 'UserController@postLogin',
      'as' => 'loginPost'
    ]);

    Route::get('/gLogout', [
      'uses' => 'UserController@getLogout',
      'as' => 'logoutGet'
    ]);
});
