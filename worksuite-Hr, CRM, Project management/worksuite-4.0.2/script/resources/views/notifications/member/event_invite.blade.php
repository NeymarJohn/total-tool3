<li class="top-notifications">
    <div class="message-center">
        <a href="{{ route('member.events.index') }}">
            <div class="user-img">
                <span class="btn btn-circle btn-success"><i class="icon-calender"></i></span>
            </div>
            <div class="mail-contnet">
                <span class="mail-desc m-0">{{ __('email.newEvent.subject') }}</span>
                <small>{{ ucfirst($notification->data['event_name']) }}</small>
                <span class="time">{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $notification->created_at)->diffForHumans() }}</span>
            </div>
        </a>
    </div>
</li>
