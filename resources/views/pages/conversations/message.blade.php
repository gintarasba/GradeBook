@php ($userInfo = $message->user()->first())
@php ($userPhoto = $userInfo->photos())
@php ($profilePic = '/users/photos/default.jpg')
@foreach ($userPhoto as $photo)
    @if ($photo->profilePic) $profilePic = $photo->photoPath; @endif
@endforeach
@php ($fullName = $userInfo->name.' '.$userInfo->second_name)
@php ($position = $key % 2 == 0 ? 'right' : 'left')
<div class="direct-chat-msg {{ $position }}">
    <div class="direct-chat-info clearfix">
        <span class="direct-chat-name pull-left">{{ $fullName }}</span>
        <span class="direct-chat-timestamp pull-right">{{ $message->created_at->format("Y-m-d H:i:s") }}</span>
    </div>
    <!-- /.direct-chat-info -->
    <img class="direct-chat-img" src="'.$profilePic.'" alt="Message User Image"><!-- /.direct-chat-img -->
    <div class="direct-chat-text">
        {{ $message->message }}
    </div>
    <!-- /.direct-chat-text -->
</div>
