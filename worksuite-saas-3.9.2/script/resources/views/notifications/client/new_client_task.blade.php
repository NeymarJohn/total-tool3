<li class="top-notifications">
    <div class="message-center">
        <a href="{{route('front.task-share',[md5($notification->data['id'])])}}" target="_blank">
            <div class="user-img">
                <span class="btn btn-circle btn-info"><i class="icon-list"></i></span>
            </div>
            <div class="mail-contnet">
                <span class="mail-desc m-0">{{ __('email.newClientTask.subject') }}</span> 
                <small>{!!  ucfirst($notification->data['heading']) !!} </small>
                <span class="time">{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $notification->created_at)->diffForHumans() }}</span>
            </div>
        </a>
    </div>
</li>
