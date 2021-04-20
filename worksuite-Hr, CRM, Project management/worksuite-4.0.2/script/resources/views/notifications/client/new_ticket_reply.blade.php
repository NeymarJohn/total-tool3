<li class="top-notifications">
    <div class="message-center">
        @php
            if (!isset($notification->data['subject'])) {
                $ticketReply = \App\TicketReply::with('ticket')->find($notification->data['id']);
                $subject = $ticketReply->ticket->subject;
            } else {
                $subject = $notification->data['subject'];
            }
        @endphp
        <a href="{{ route('client.tickets.edit',  $notification->data['id']) }}">
            <div class="user-img">
                <span class="btn btn-circle btn-warning"><i class="ti-ticket"></i></span>
            </div>
            <div class="mail-contnet">
                <span class="mail-desc m-0">{{ __('email.ticketReply.subject') . ' #' . $notification->data['id']}}</span> 
                <small>{{ $subject }}</small>
                <span class="time">{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $notification->created_at)->diffForHumans() }}</span>
            </div>
        </a>
    </div>
</li>
