<li class="top-notifications">
    <div class="message-center">
        <a href="javascript:;" onclick="showTaskDetail({{ $notification->data['id'] }})" >
            <div class="user-img">
                <span class="btn btn-circle btn-success"><i class="fa fa-tasks"></i></span>
            </div>
            <div class="mail-contnet">
                <span class="mail-desc m-0">@lang('email.taskComplete.subject')</span>
                <small>{{ ucfirst($notification->data['heading']) }}</small> 
                <span class="time">{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $notification->created_at)->diffForHumans() }}</span>
            </div>
        </a>
    </div>
</li>
