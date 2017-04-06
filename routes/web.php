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


Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
    Route::post('pLogin', ['as' => 'loginPost', 'uses' => 'UserController@postLogin']);
    Route::get('gLogout', ['as' => 'logoutGet', 'uses' => 'UserController@getLogout']);
});


// Su api raktu
Route::group(['middleware' => 'App\Http\Middleware\RasberryApiMiddleware'], function () {
    Route::group(['prefix' => 'api'], function () {
        Route::get('status/{appId}',  [
            'uses' => 'RasberryApiController@checkStatus',
            'as'   => 'checkStatus'
        ])->where('appId', '[0-9]+');

        Route::post('screenshot', [
            'uses' => 'RasberryApiController@userCheckpointEvent',
            'as'   => 'userCheckpointEvent'
        ]);
    });
});






// Visiems prisijungusiems
Route::group(['middleware' => 'App\Http\Middleware\AuthCheckMiddleware'], function () {
    Route::group(['prefix' => 'user'], function () {
        Route::get('profile/{userId?}', [
            'uses' => 'UserController@showProfile',
            'as'   => 'showProfile'
        ])->where('userId', '[0-9]+');

        Route::get('newPass/{passLength?}', [
            'uses' => 'UserController@generateNewPassword',
            'as' =>'generateNewPassword'
        ])->where('passLength', '[0-9]+');

        Route::post('update/info', [
            'uses' => 'UserController@updateUserInformation',
            'as' => 'updateUserInformation'
        ]);
    });

    Route::group(['as' => 'conversation.'], function () {
        Route::get('messagesCenter', ['as' => 'list', 'uses' => 'ConversationController@showConversations']);
        Route::post('sendMessage', ['as' => 'sendMessage', 'uses' => 'ConversationController@sendMessage']);
        Route::get('suggestions', ['as' => 'suggestions', 'uses' => 'ConversationController@suggestList']);
        Route::get('conversation', ['as' => 'newConversation', 'uses' => 'ConversationController@addNewConversation']);
        Route::get('messages', ['as' => 'messages', 'uses' => 'ConversationController@messagesList']);
    });
});


// Tik su specialiu leidimu
Route::group(['middleware' => [
    'App\Http\Middleware\AuthCheckMiddleware',
    'App\Http\Middleware\PermitsMiddleware']],
function () {
    Route::get('/user/gradeBook', [
        'uses' => 'GradeBookController@showMyMarks',
        'as' => 'showMyMarks'
    ]);

    Route::get('/user/gBook/{user}', [
        'uses' => 'GradeBookController@showUserGrades',
        'as' => 'showUserGrades'
    ])->where('user', '[0-9]+');

    Route::get('/user/updateGrade', [
        'uses' => 'GradeBookController@updateGrade',
        'as' => 'updateGrade'
    ]);

    Route::get('/users/grades/{group?}', [
        'uses' => 'GradeBookController@showUsersGrades',
        'as' => 'showUsersGrades'
    ])->where('group', '[0-9]+');
});






Route::group(['prefix' => 'admin', 'middleware' => 'App\Http\Middleware\AdminMiddleware'], function () {

    /**
     * UserController
     *
    */

    Route::get('/user/allInfo/{userId}', [
        'uses' => 'UserController@getDataAboutUser',
        'as' => 'getDataAboutUser'
    ])->where('userId', '[0-9]+');


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



    Route::get('/user/newLogin', [
        'uses' => 'UserController@generateNewLoginName',
        'as' =>'generateNewLoginName'
    ]);

    Route::get('/users/list/json', [
        'uses' => 'UserController@getUsersListByKeyword',
        'as' =>'getUsersListByKeyword'
    ]);

    Route::get('/users/get/json', [
        'uses' => 'UserController@getUserInfo',
        'as' =>'getUserInfo'
    ]);

    Route::get('/user/delete', [
        'uses' => 'UserController@dellUserComp',
        'as' => 'dellUserComp'
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

    Route::get('/goups/new', [
        'uses' => 'GroupController@newGroupTitle',
        'as' => 'newGroupTitle'
    ]);

    Route::get('/groups/add/user', [
        'uses' => 'GroupController@addUserToGroup',
        'as' => 'addUserToGroup'
    ]);

    Route::get('/groups/detach/user', [
        'uses' => 'GroupController@detachFromGroup',
        'as' => 'detachFromGroup'
    ]);

    Route::get('/groups/user/attach', [
        'uses' => 'GroupController@attachGroupToUser',
        'as' => 'attachGroupToUser'
    ]);
    /**
     * MarksController
     *
    */
    Route::get('/mark/user/json', [
        'uses' => 'GradeBookController@getMarksJson',
        'as' => 'getMarksJson'
    ]);

    /**
     * SubjectController
     *
    */
   Route::get('/group/subjects', [
      'uses' => 'SubjectController@newSubjectForm',
      'as' => 'newSubjectForm'
   ]);

    Route::get('/subjects/new', [
       'uses' => 'SubjectController@newSubjectTitle',
       'as' => 'newSubjectTitle'
   ]);

    Route::get('/subject/user/attach', [
       'uses' => 'SubjectController@attachSubjectToUser',
       'as' => 'attachSubjectToUser'
   ]);

    Route::get('/subject/detach/user', [
       'uses' => 'SubjectController@detachFromSbj',
       'as' => 'detachFromSubject'
   ]);
});
