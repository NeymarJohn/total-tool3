<li class="top-notifications">
    <div class="message-center">
        <a href="{{route('admin.contracts.show',[md5($notification->data['id'])])}}#discussion" target="_blank">
            <div class="user-img">
                <span class="btn btn-circle btn-info"><i class="fa fa-tasks"></i></span>
            </div>
            <div class="mail-contnet">
                <span class="mail-desc m-0">@lang('modules.contracts.contractCommect')</span>
                <small>{{ ucfirst($notification->data['subject']) }}</small>
                <span class="time">@if($notification->created_at){{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $notification->created_at)->diffForHumans() }}@endif</span>
            </div>
        </a>
    </div>
</li>
