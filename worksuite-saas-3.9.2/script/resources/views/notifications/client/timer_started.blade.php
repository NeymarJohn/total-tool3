<li class="top-notifications">
    <div class="message-center">
        <a href="javascript:;">
            <div class="user-img">
                <span class="btn btn-circle btn-danger"><i class="icon-clock"></i></span>
            </div>
            <div class="mail-contnet">
                <span class="mail-desc m-0">Timer Started for Project - {{ ucwords($notification->data['project']['project_name']) }}</span> <span class="time">{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $notification->created_at)->diffForHumans() }}</span>
            </div>
        </a>
    </div>
</li>
