<li class="top-notifications">
    <div class="message-center">
        <a href="{{ route('admin.contracts.show', md5($notification->data['id']))  }}" target="_blank">
            <div class="user-img">
                <span class="btn btn-circle btn-info"><i class="fa fa-file"></i></span>
            </div>
            <div class="mail-contnet">
                <span class="mail-desc m-0">{{ __('email.contractSign.subject') }} </span> 
                <small>{{ ucfirst($notification->data['subject']) }}</small>
                <span class="time">{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $notification->created_at)->diffForHumans() }}</span>
            </div>
        </a>
    </div>
</li>