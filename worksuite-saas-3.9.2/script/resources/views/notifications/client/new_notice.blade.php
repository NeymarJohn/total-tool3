<li class="top-notifications">
    <div class="message-center">
        <a href="{{ route('client.notices.index') }}">
            <div class="user-img">
                <span class="btn btn-circle btn-info"><i class="ti-layout-media-overlay"></i></span>
            </div>
            <div class="mail-contnet">
                <span class="mail-desc m-0">@lang('email.newNotice.subject')</span> 
                <small>{{ ucfirst($notification->data['heading']) }}</small>
                <span class="time">{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $notification->created_at)->diffForHumans() }}</span>
            </div>
        </a>
    </div>
</li>