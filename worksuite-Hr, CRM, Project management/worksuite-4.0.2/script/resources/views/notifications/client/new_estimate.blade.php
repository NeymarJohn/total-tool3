<li class="top-notifications">
    <div class="message-center">
        @php
            if ($notification->data['estimate_number'] == "") {
                $estimate = \App\Estimate::find($notification->data['id']);
                $estimateNumber = $estimate->estimate_number;
            } else {
                $estimateNumber = $notification->data['estimate_number'];
            }
        @endphp
        <a href="{{ route("front.estimate.show", md5($notification->data['id'])) }}" target="_blank">
            <div class="user-img">
                <span class="btn btn-circle btn-inverse"><i class="fa fa-money fa-fw"></i></span>
            </div>
            <div class="mail-contnet">
                <span
                    class="mail-desc m-0">{{ __('email.estimate.subject') }}</span>
                    <small>{{ $estimateNumber }}</small>
                <span class="time">{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $notification->created_at)->diffForHumans() }}</span>
            </div>
        </a>
    </div>
</li>