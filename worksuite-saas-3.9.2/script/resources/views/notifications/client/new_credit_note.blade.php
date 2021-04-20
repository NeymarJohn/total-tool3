<li class="top-notifications">
    <div class="message-center">
        <a href="{{ route('client.credit-notes.index') }}">
            <div class="user-img">
                <span class="btn btn-circle btn-inverse"><i class="fa fa-money fa-fw"></i></span>
            </div>
            <div class="mail-contnet">
                <span
                    class="mail-desc m-0">{{ __('email.creditNote.subject') }}</span>
                    <small>{{ $notification->data['cn_number'] }}</small>
                <span class="time">{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $notification->created_at)->diffForHumans() }}</span>
            </div>
        </a>
    </div>
</li>