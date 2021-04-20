<li class="top-notifications">
    <div class="message-center">
        <a href="{{ route('admin.all-invoices.show', $notification->data['id']) }}">
            <div class="user-img">
                <span class="btn btn-circle btn-inverse"><i class="fa fa-money fa-fw"></i></span>
            </div>
            <div class="mail-contnet">
                <span class="mail-desc m-0">{{ __('email.invoices.paymentReceived') }}</span>
                <small>{{ $notification->data['invoice_number'] }}</small>
                <span class="time">{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $notification->created_at)->diffForHumans() }}</span>
            </div>
        </a>
    </div>
</li>