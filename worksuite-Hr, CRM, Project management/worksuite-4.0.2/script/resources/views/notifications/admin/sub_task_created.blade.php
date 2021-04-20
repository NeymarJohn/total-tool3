<li class="top-notifications">
    <div class="message-center">
        <a href="javascript:;" onclick="showTaskDetail({{ $notification->data['id'] }})" >
            <div class="user-img">
                <span class="btn btn-circle btn-info"><i class="fa fa-tasks"></i></span>
            </div>
            <div class="mail-contnet">
                <span class="mail-desc m-0">@lang('email.subTaskCreated')</span> 
                <small>{{ ucfirst($notification->data['heading']) }}</small>
                <span class="time">@if($notification->created_at){{ \Carbon\Carbon::parse( $notification->created_at)->diffForHumans() }}@endif</span>
            </div>
        </a>
    </div>
</li>
