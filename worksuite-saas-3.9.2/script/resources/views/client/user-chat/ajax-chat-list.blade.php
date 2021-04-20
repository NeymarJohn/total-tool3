@forelse($chatDetails as $chatDetail)

    <li class="@if($chatDetail->from == $user->id) odd @else  @endif">
        <div class="chat-image"> <img alt="user" src="{{$chatDetail->fromUser->image_url}}"> </div>
        <div class="chat-body">
            <div class="chat-text">
                @if(($chatDetail->from == $user->id))
                <div class="messageDelete @if($chatDetail->from == $user->id) left @else right @endif" onclick="deleteMessage('{{ $chatDetail->id }}')"><i class="fa fa-trash"></i></div>
                    @endif
                <h4>@if($chatDetail->from == $user->id) you @else {{$chatDetail->fromUser->name}} @endif</h4>
                <p>{{ $chatDetail->message }}</p>
                <b>{{ $chatDetail->created_at->timezone($global->timezone)->format('d M, h:i A') }}</b>
            </div>
        </div>
    </li>

@empty
    <li><div class="message">@lang('messages.noMessage')</div></li>
@endforelse
