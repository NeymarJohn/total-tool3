<li class="top-notifications">
    <div class="message-center">
        <a href="javascript:;">
            <div class="user-img">
                <span class="btn btn-circle btn-inverse"><i class="icon-doc"></i></span>
            </div>
            <div class="mail-contnet">
                <span class="mail-desc m-0">@lang('app.new') @lang('app.menu.payments') </span> <span class="time">{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $notification->created_at)->diffForHumans() }}</span>
            </div>
        </a>
    </div>
</li>
