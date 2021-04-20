<li class="top-notifications">
    <div class="message-center">
        <a href="{{ route('member.leaves.index') }}">
            <div class="user-img">
                <span class="btn btn-circle btn-warning"><i class="icon-logout"></i></span>
            </div>
            <div class="mail-contnet">
                <span class="mail-desc m-0">@lang('email.leaves.statusSubject')</span>
                <small>{{ \Carbon\Carbon::parse($notification->data['leave_date'])->format(company_setting()->date_format) }}</small>
                <span class="time">{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $notification->created_at)->diffForHumans() }}</span>
            </div>
        </a>
    </div>
</li>
