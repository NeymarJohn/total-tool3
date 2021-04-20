@forelse($userLists as $userList)

    <li id="dp_{{$userList->id}}">
        <a href="javascript:void(0)" id="dpa_{{$userList->id}}"
           class="@if(isset($userID) && $userID == $userList->id) active @endif"
           onclick="getChatData('{{$userList->id}}', '{{$userList->name}}')">

            <img src="{{ $userList->image_url }}" alt="user-img" class="img-circle">
            <span @if($userList->message_seen == 'no' && $userList->user_one != $user->id) class="font-bold" @endif>{{$userList->name}}
                <small class="text-simple">@if($userList->last_message){{  \Carbon\Carbon::parse($userList->last_message)->diffForHumans()}} @endif
                    @if(\App\User::isAdmin($userList->id))
                        <label class="btn btn-danger btn-xs btn-outline">Admin</label>
                    @elseif(\App\User::isClient($userList->id))
                        <label class="btn btn-success btn-xs btn-outline">Client</label>
                    @else
                        <label class="btn btn-warning btn-xs btn-outline">Employee</label>
                    @endif
                </small>

            </span>
        </a>
    </li>

@empty
    <li>
        <a href="javascript:void(0)">
            <span>
                @lang('messages.noConversation')
            </span>
        </a>
    </li>
@endforelse
