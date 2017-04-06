@extends('layouts.master')

@section('title')
    ProjectKK
@endsection

@section('content')



<div class="content-wrapper niceBg" >
    <section class="content ">
        <div class="row">
            <div class="col-md-2">
                @include('includes.aside')
            </div>

            <div class="col-md-10">
                <div class="row">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Pagrindinis</a></li>
                        <li class="breadcrumb-item active">Žinutės</li>
                    </ol>

                    <div class="col-md-12">
                        <div class="panel panel-primary">
                            <div class="panel-heading">Žinutės</div>
                            <div class="panel-body">
                                <div class="row" style="margin-bottom: 10px;">
                                    <div class="col-md-4">
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="searchKeyword" placeholder="Paieška">
                                            <span class="input-group-addon btn btn-block btn-success btn-flat createConversation">Pridėti</span>
                                            <div id="suggest" style="display:none; top: 34px; left: 0;"></div>
                                            <div id="suggestedId" style="display: none;"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xs-2"> <!-- required for floating -->
                                        <!-- Nav tabs -->
                                        <ul class="nav nav-tabs tabs-left conversationsTabs">
                                        @php ($messagesOut = '')
                                        @php foreach($conversationsList AS $KEY => $conversation) {
                                            $usersList = $conversation->users()->get();
                                            $usersOut = array();
                                            foreach($usersList AS $usr) {
                                                $usersOut[] = $usr->name.' '.$usr->second_name;
                                            }
                                            $nameTitle = ' ('.implode(', ',$usersOut).')';
                                            echo '
                                            <li class="conversationTab '.($KEY == 0 ? 'active' : '').'">
                                                <a id="tabId" href="#conversationId_'.$conversation->id.'" tabId="'.$conversation->id.'" data-toggle="tab">'.$conversation->title.''.(\App\FancyLib\Functions::truncate($nameTitle, 50)).'</a>
                                            </li>';

                                            $messagesOut .= '
                                            <div class="tab-pane '.($KEY == 0 ? 'active' : '').'" id="conversationId_'.$conversation->id.'"><div class="row">
                                                <div class="col-md-8" style="padding-left: 0px;">
                                                    <div class="box box-success direct-chat direct-chat-success">
                                                        <div class="box-header with-border">
                                                            <h3 class="box-title">'.$conversation->title.''.(\App\FancyLib\Functions::truncate($nameTitle, 50)).'</h3>
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
                                                                <!-- Message. Default to the left -->';
                                                                $messagesList = $conversation->messages()->orderBy('id', 'desc')->take(20)->get()->reverse();
                                                                $usersOut = array();
                                                                $userInfo = array();
                                                                foreach($messagesList AS $KEY => $message) {
                                                                    if(!isset($userInfo[$message->user_id])) {
                                                                        $userInfo[$message->user_id]['user'] = $message->user()->first();
                                                                        $userInfo[$message->user_id]['photos'] = $userInfo[$message->user_id]['user']->photos();
                                                                        $userInfo[$message->user_id]['profilePic'] = '/users/photos/default.jpg';
                                                                        foreach ($userInfo[$message->user_id]['photos'] as $photo) {
                                                                            if ($photo->profilePic) {
                                                                                $userInfo[$message->user_id]['profilePic'] = $photo->photoPath;
                                                                            }
                                                                        }
                                                                    }

                                                                    $profilePic = $userInfo[$message->user_id]['profilePic'];
                                                                    $fullName = $userInfo[$message->user_id]['user']->name.' '.$userInfo[$message->user_id]['user']->second_name;
                                                                    $messagesOut .= '
                                                                    <div messageId="'.$message->id.'" class="direct-chat-msg '.($message->right == 1 ? 'right' : '').'">
                                                                        <div class="direct-chat-info clearfix">
                                                                            <span class="direct-chat-name pull-left">'.$fullName.'</span>
                                                                            <span class="direct-chat-timestamp pull-right">'.$message->created_at->format("Y-m-d H:i:s").'</span>
                                                                        </div>
                                                                        <!-- /.direct-chat-info -->
                                                                        <img class="direct-chat-img" src="'.$profilePic.'" alt="Message User Image"><!-- /.direct-chat-img -->
                                                                        <div class="direct-chat-text">
                                                                            '.$message->message.'
                                                                        </div>
                                                                        <!-- /.direct-chat-text -->
                                                                    </div>';
                                                                    $usersOut[$userInfo[$message->user_id]['user']->id] = '
                                                                    <li>
                                                                        <a href="#">
                                                                            <img class="contacts-list-img" src="'.$profilePic.'" alt="User Image">
                                                                            <div class="contacts-list-info">
                                                                                <span class="contacts-list-name">
                                                                                  '.$fullName.'
                                                                                </span>
                                                                            </div>
                                                                        </a>
                                                                    </li>';
                                                                }
                                                                $usersOutHtml = '';
                                                                foreach($usersOut AS $user) {
                                                                    $usersOutHtml .= $user;
                                                                }
                                                                $messagesOut .= '
                                                            </div>
                                                            <div class="box-footer">
                                                                <form>
                                                                    <input type="hidden" id="conversationId" value="'.$conversation->id.'">
                                                                    <div class="input-group">
                                                                        <textarea class="form-control custom-control" id="message" rows="3" cols="20" style="resize:none"></textarea>
                                                                        <span class="input-group-addon btn btn-block btn-success btn-flat sendMessage">Siųsti</span>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div></div>
                                                </div>
                                            </div>';
                                        } @endphp
                                    </ul>
                                </div>

                                <div class="col-xs-9">
                                    <!-- Tab panes -->
                                    <div class="tab-content conversationsContents">
                                        {!! $messagesOut !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                      </div>
                  </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script>
    var _token = '{{ Session::token() }}';
    $('.sendMessage').on('click', function() {
        sendMessage(this);

    });

    function sendMessage(thi) {
        var conversationId = $(thi).parent().parent().find('#conversationId').val();
        var message = $('#conversationId_' + conversationId).find('#message').val();
        $('#conversationId_' + conversationId).find('#message').val('');
        $.ajax({
            url: "{{ route('conversation.sendMessage') }}",
            type: 'POST',
            dataType: 'json',
            data: {
                _token: _token,
                convId: conversationId,
                message: message
            }
        }).always(function(out) {
            if(out.success == true) {
                var newMessage = $(out.newMessage);

                $('#conversationId_' + conversationId + ' .direct-chat-messages').append(newMessage);
                scrollToBottom();
            }
        });
    }

    $('#searchKeyword').on('keyup', function() {
        var keyword = $(this).val();
        if(keyword.length <= 0) {
            $('#suggest').attr('style', 'display: none;').html(' ');
            return ;
        }
        $.ajax({
            url: "{{ route('conversation.suggestions') }}",
            type: 'GET',
            dataType: 'json',
            data: {keyword: keyword}
        }).always(function(out) {
            console.log("complete", out);
            if(out.success == true) {
                showSuggestions(out.suggestList, keyword);
            }
        });

    });

    $('.createConversation').on('click', function() {
        var partnerId = $('#suggestedId').val();
        $.ajax({
            url: "{{ route('conversation.newConversation') }}",
            type: 'GET',
            dataType: 'json',
            data: {partnerId: partnerId}
        }).always(function(out) {
            console.log("complete", out);
            if(out.success == true) {
                if(out.found == true) {

                } else {
                    $('.conversationTab.active').removeClass('active');
                    $('.tab-pane.active').removeClass('active');

                    var tab = $(out.tab).addClass('active');
                    var content = $(out.content).addClass('active');

                    $('.conversationsTabs').prepend(tab);
                    $('.conversationsContents').prepend(content)
                }
            }
        });

    });

    var lastMessageId = null;
    function poolMessages()
    {
         var conversationId = $('.conversationTab.active').find('#tabId').attr('tabId');
         var lastMessageId = $('#conversationId_' + conversationId + ' .direct-chat-messages .direct-chat-msg:last-child').attr('messageId');
         $.ajax({
             url: "{{ route('conversation.messages') }}",
             type: 'GET',
             dataType: 'json',
             data: {convId: conversationId, lastMID: lastMessageId}
         }) .always(function(out) {
             if(out.success == true) {
                 for(var key in out.messages) {
                     var message = out.messages[key];
                     var messageHtml = $('<div messageId="'+message.id+'" class="direct-chat-msg '+(message.right == 1 ? 'right' : '')+'">'+
                         '<div class="direct-chat-info clearfix">'+
                             '<span class="direct-chat-name pull-left">'+message.fullName+'</span>'+
                             '<span class="direct-chat-timestamp pull-right">'+message.data+'</span>'+
                         '</div>'+
                         '<img class="direct-chat-img" src="'+message.profilePic+'" alt="Message User Image">'+
                         '<div class="direct-chat-text">'+message.message+
                         '</div>'+
                     '</div>');

                     $('#conversationId_' + conversationId + ' .direct-chat-messages').append(messageHtml);
                 }

                if(out.messages.length > 0) {
                    return ;
                }
                if($('#conversationId_' + conversationId + ' .direct-chat-messages .direct-chat-msg').length > 20) {
                    $('#conversationId_' + conversationId + ' .direct-chat-messages .direct-chat-msg:first-child').remove();
                }
             }
         });

    }

    poolMessages();

    function startChat(){
      setInterval( function() { poolMessages(); }, 2000);
    }
    //startChat();



    function showSuggestions(suggestionList, keyword) {
        html = '';
        list = [];

        var i=0;
        for(var key in suggestionList) {
            if(i>10)break;

            var suggestion = suggestionList[key];
            list[key] = suggestion.name + ' ' + suggestion.second_name + ' ' + suggestion.loginName;
            html += '<div id="suggestedId_'+suggestion.id+'" onclick="replaceSuggestion(this, \''+suggestion.loginName+'\',\''+suggestion.id+'\');">'+list[key].replace(keyword, '<b>'+keyword+'</b>')+'</div>';
            i++;
        }
        $('#suggest').css('display', 'block').html(html);
    }

    function replaceSuggestion(me, replacable, id) {
        var thi = $(this);
        $('#searchKeyword').val(replacable);

        $('#suggest').css('display', 'none').html(' ');
        $('#suggestedId').val(id);
    }
    function scrollToBottom() {
        $('.direct-chat-messages').scrollTop($(".direct-chat-messages")[0].scrollHeight);
    }
    scrollToBottom();
</script>
@endsection


@if(Auth::check())
    @section('javascripts')
        <script src="{{ URL::to('AdminLTE-2.3.5/dist/js/custom.js') }}"></script>

    @endsection
@endif
