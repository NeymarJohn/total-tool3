<li class="top-notifications">
    <div class="message-center">
        <a href="{{ route('admin.project-ratings.show', $notification->data['id']) }}">
            <div class="user-img">
                <span class="btn btn-circle btn-success"><i class="ti-comments"></i></span>
            </div>
            <div class="mail-contnet">
                <span class="mail-desc m-0">{{ __('email.rating.subject') }}</span>
                <small>{{ $notification->data['project_name'] }}</small>
                <span class="time">@if($notification->created_at){{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $notification->created_at)->diffForHumans() }}@endif</span>
            </div>
        </a>
    </div>
</li>
