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
use \App\FancyLib\Functions;
use \App\UserMessage;
use DB;
use \App\Conversation;

class ConversationController extends Controller
{
    public function showConversations()
    {
        $user = Auth::user();
        $conversationsList = $user->conversations()->orderBy('updated_at', 'DESC')->get();

        return view('pages.conversations.allMessages', [
            'conversationsList' => $conversationsList
        ]);
    }

    public function sendMessage()
    {
        if (!Input::has('_token')) {
            return Response::json([
                'success' => false,
                'error' => 'Prašome perkrauti puslapį.'
            ]);
        }

        if (Session::token() !== e(Input::get('_token'))) {
            return Response::json([
                'success' => false,
                'error' => 'Blogas tokenas, prašome perkrauti puslapį.'
            ]);
        }

        if (!Input::has('convId')) {
            return Response::json([
                'success' => false,
                'error' => 'Nežinomas pokalbis'
            ]);
        }

        if (!Input::has('message')) {
            return Response::json([
                'success' => false,
                'error' => 'Neįvestas pranešimas!'
            ]);
        }
        $conversationId = Functions::cleaner(Input::get('convId'), 'num');
        $user = Auth::user();
        $conversation = $user->conversations()->where('conversation_id', $conversationId)->first();
        $message = Functions::cleaner(Input::get('message'));

        if (is_null($conversation)) {
            return Response::json([
                'success' => false,
                'error' => 'Pokalbis nerastas!'
            ]);
        }

        $userPhoto = $user->photos();
        $profilePic = '/users/photos/default.jpg';
        foreach ($userPhoto as $photo) {
            if ($photo->profilePic) {
                $profilePic = $photo->photoPath;
            }
        }
        $lastMessage = $conversation->messages()->orderBy('id', 'desc')->take(1)->first();
        $right = 0;
        if (is_null($lastMessage)) {
            $right = 1;
        } elseif ($lastMessage->user_id == $user->id) {
            $right = $lastMessage->right;
        } else {
            $right = !$lastMessage->right;
        }

        $newMessage = new UserMessage;
        $newMessage->user_id = $user->id;
        $newMessage->conversation_id = $conversation->id;
        $newMessage->message = $message;
        $newMessage->right = $right;
        $newMessage->save();
        $fullName = $user->name.' '.$user->second_name;
        $messageHtml = '
        <div messageId="'.$newMessage->id.'" class="direct-chat-msg '.($right == 1 ? 'right' : '').'">
            <div class="direct-chat-info clearfix">
                <span class="direct-chat-name pull-left">'.$fullName.'</span>
                <span class="direct-chat-timestamp pull-right">'.$newMessage->created_at->format("Y-m-d H:i:s").'</span>
            </div>
            <img class="direct-chat-img" src="'.$profilePic.'" alt="Message User Image">
            <div class="direct-chat-text">
                '.$message.'
            </div>
        </div>';
        return Response::json(['success' => true, 'newMessage' => $messageHtml]);
    }

    public function suggestList()
    {
        if (!Input::has('keyword')) {
            return Response::json([
                'success' => false,
                'error' => 'Neįvestas vardas!'
            ]);
        }

        $keyword = Functions::cleaner(Input::get('keyword'));

        $users = DB::table('users')
            ->select('id', 'name', 'second_name', 'loginName', DB::raw('CONCAT(name, \' \', second_name, \' \', loginName) AS fullKeyword'))
            ->whereRaw("CONCAT(name, ' ', second_name, ' ', loginName) LIKE '%".$keyword."%'")
            ->get();


       //$users = User::search($keyword, null, true)->get();
        return Response::json(['success' => true, 'suggestList' => $users]);
    }

    public function addNewConversation()
    {
        if (!Input::has('partnerId')) {
            return Response::json([
                'success' => false,
                'error' => 'Neįvestas vardas!'
            ]);
        }

        $partnerId = Functions::cleaner(Input::get('partnerId'), 'num');
        $partner = User::where('id', $partnerId)->first();
        if (is_null($partner)) {
            return Response::json([
                'success' => false,
                'error' => 'Neįvestas vardas!'
            ]);
        }

        $conversations = Auth::user()->conversations()->get();
        $partnerExists = false;

        foreach ($conversations as $conv) {
            $partnerEx = $conv->users()->where('user_id', $partnerId)->first();
            $users =  $conv->users()->get();
            $uCount = count($users);
            if (!is_null($partnerEx) & $uCount == 2) {
                $partnerExists = $conv;
                break;
            }
        }



        if ($partnerExists == false) {
            $newConversation = new Conversation;
            $newConversation->save();
            $newConversation->touch();

            Auth::user()->conversations()->attach($newConversation);
            $partner->conversations()->attach($newConversation);
            $nameTitle = ' ('.Auth::user()->name.' '.Auth::user()->second_name.', '.$partner->name.' '.$partner->second_name.')';

            $conversationTab = '<li class="conversationTab">
                <a id="tabId" href="#conversationId_'.$newConversation->id.'" tabId="'.$newConversation->id.'" data-toggle="tab">'.$newConversation->title.''.(Functions::truncate($nameTitle, 50)).'</a>
            </li>';

            $conversationContent = '<div class="tab-pane" id="conversationId_'.$newConversation->id.'"><div class="row">
                <div class="col-md-8">
                    <div class="box box-success direct-chat direct-chat-success">
                        <div class="box-header with-border">
                            <h3 class="box-title">'.$newConversation->title.''.(Functions::truncate($nameTitle, 50)).'</h3>
                            <div class="box-tools pull-right">
                                <span data-toggle="tooltip" title="3 New Messages" class="badge bg-green">3</span>
                                <!-- <button type="button" class="btn btn-box-tool" data-toggle="tooltip" title="" data-widget="chat-pane-toggle" data-original-title="Contacts">
                                <i class="fa fa-comments"></i></button> -->
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <!-- Conversations are loaded here -->
                            <div class="direct-chat-messages">
                            </div>
                        </div>
                        <div class="box-footer">
                            <form>
                                <input type="hidden" id="conversationId" value="'.$newConversation->id.'">
                                <div class="input-group">
                                    <textarea class="form-control custom-control" id="message" rows="3" cols="20" style="resize:none"></textarea>
                                    <span class="input-group-addon btn btn-block btn-success btn-flat" onclick="sendMessage(this);">Siųsti</span>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            </div>';

            return Response::json([
                'success' => true,
                'found' => false,
                'conversation' => $newConversation->toArray(),
                'tab' => $conversationTab,
                'content' => $conversationContent,
            ]);
        } else {
            return Response::json([
                'success' => true,
                'found' => true,
                'conversation' => $partnerExists->toArray()
            ]);
        }
    }

    public function messagesList()
    {
        if (!Input::has('convId')) {
            return Response::json([
                'success' => false,
                'error' => 'Nežinomas pokalbis'
            ]);
        }

        if (!Input::has('lastMID')) {
            return Response::json([
                'success' => false,
                'error' => 'Nežinomas pokalbis'
            ]);
        }

        $conversationId = Functions::cleaner(Input::get('convId'), 'num');
        $lastMID = Functions::cleaner(Input::get('lastMID'), 'num');
        $user = Auth::user();
        $conversation = $user->conversations()->where('conversation_id', $conversationId)->first();

        if (is_null($conversation)) {
            return Response::json([
                'success' => false,
                'error' => 'Nežinoma žinutė.'
            ]);
        }

        $messagesList = $conversation->messages()->where('id', '>', $lastMID)->get();

        $messages = array();
        foreach ($messagesList as $message) {
            $user = $message->user()->first();
            $userPhoto = $user->photos();
            $profilePic = '/users/photos/default.jpg';
            foreach ($userPhoto as $photo) {
                if ($photo->profilePic) {
                    $profilePic = $photo->photoPath;
                }
            }

            $fullName = $user->name.' '.$user->second_name;
            $messages[] = array(
                'right' => $message->right,
                'fullName' => $fullName,
                'data' => $message->created_at->format("Y-m-d H:i:s"),
                'profilePic' => $profilePic,
                'message' => $message->message
            );
        }

        return Response::json([
            'success' => true,
            'messages' => $messages
        ]);
    }
}
