<li class="top-notifications">
    <div class="message-center">
        <a href="{{route('admin.leads.show',[$notification->data['id']])}}" target="_blank">
            <div class="user-img">
                <span class="btn btn-circle btn-info"><i class="icon-list"></i></span>
            </div>
            <div class="mail-contnet">
                <span class="mail-desc m-0">{{ __('email.lead.subject') }}</span>
                <small>{{ __('email.lead.text') }} {{ __('email.lead.withName') }} - {{ ucfirst($notification->data['company_name']) }}</small>
                <span class="time">@if($notification->created_at){{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $notification->created_at)->diffForHumans() }}@endif</span>
            </div>
        </a>
    </div>
</li>
