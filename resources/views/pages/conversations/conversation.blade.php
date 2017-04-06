<div class="tab-pane active" id="home">
    <div class="row">
        <div class="col-md-8">
            <div class="box box-success direct-chat direct-chat-success">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ $conversation->title }}</h3>

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
                        <!-- Message. Default to the left -->
                        @php ($messagesList = $conversation->messages()->orderBy('id', 'DESC')->take(25)->get())
                        @foreach($messagesList AS $key => $message)
                            @include('pages.conversations.message', ['key' => $key, 'message' => $message])
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
