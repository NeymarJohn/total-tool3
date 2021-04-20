<li class="top-notifications">
    <div class="message-center">
        <a href="{{ route('admin.leaves.pending') }}">
            <div class="user-img">
                <span class="btn btn-circle btn-warning"><i class="icon-logout"></i></span>
            </div>
            <div class="mail-contnet">
                <span class="mail-desc m-0">@lang('email.leaves.subject')</span> 
                <small>{{ ucwords($notification->data['user']['name']) }}</small>
                <span class="time">{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $notification->created_at)->diffForHumans() }}</span>
            </div>
        </a>
    </div>
</li>
