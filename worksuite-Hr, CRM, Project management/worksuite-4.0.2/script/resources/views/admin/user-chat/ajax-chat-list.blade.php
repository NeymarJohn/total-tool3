@forelse($chatDetails as $chatDetail)

    <li class="@if($chatDetail->from == $user->id) odd @else  @endif">
        <div class="chat-image"> <img alt="user" src="@if(is_null($chatDetail->fromUser->image)){{asset('img/default-profile-3.png')}} @else{{ asset_url('avatar/'.$chatDetail->fromUser->image)}}@endif"> </div>
        <div class="chat-body">
            <div class="chat-text">
                <h4>@if($chatDetail->from == $user->id) you @else {{$chatDetail->fromUser->name}} @endif</h4>
                <p>{{ $chatDetail->message }}</p>
                <b>{{ $chatDetail->created_at->timezone($global->timezone)->format($global->date_format.' '. $global->time_format) }}</b>
            </div>
        </div>
    </li>

@empty
    <li><div class="message">@lang('messages.noMessage')</div></li>
@endforelse