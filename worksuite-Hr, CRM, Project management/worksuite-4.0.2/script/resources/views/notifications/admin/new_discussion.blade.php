<li class="top-notifications">
    <div class="message-center">
        <a href="{{ route('admin.projects.discussion', $notification->data['project_id']) }}">
            <div class="user-img">
                <span class="btn btn-circle btn-success"><i class="ti-comments"></i></span>
            </div>
            <div class="mail-contnet">
                <span class="mail-desc m-0">{{ __('email.discussion.subject') }}</span> 
                <small>{{ $notification->data['title'] }}</small>
                <span class="time">@if($notification->created_at){{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $notification->created_at)->diffForHumans() }}@endif</span>
            </div>
        </a>
    </div>
</li>
