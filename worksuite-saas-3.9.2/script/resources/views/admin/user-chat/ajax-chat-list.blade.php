@forelse($chatDetails as $chatDetail)

    <li class="@if($chatDetail->from == $user->id) odd @else  @endif">
        <div class="chat-image">
            @if(is_null($chatDetail->fromUser->image))
                <img src="{{ asset('img/default-profile-3.png') }}" alt="user-img"
                     class="img-circle">
            @else
                <img src="{{ asset_url('avatar/' . $chatDetail->fromUser->image) }}" alt="user-img"
                     class="img-circle">
            @endif
        </div>
        <div class="chat-body">
            <div class="chat-text">
                @if(($chatDetail->from == $user->id))
                    <div class="messageDelete @if($chatDetail->from == $user->id) left @else right @endif" onclick="deleteMessage('{{ $chatDetail->id }}')"><i class="fa fa-trash"></i></div>
                @endif
                <h4>@if($chatDetail->from == $user->id) you @else {{$chatDetail->fromUser->name}} @endif</h4>
                <p>{{ $chatDetail->message }}</p>
                <b>{{ $chatDetail->created_at->timezone($global->timezone)->format($global->date_format.' '. $global->time_format) }}</b>
            </div>
        </div>
    </li>

@empty
    <li><div class="message">@lang('messages.noMessage')</div></li>
@endforelse
