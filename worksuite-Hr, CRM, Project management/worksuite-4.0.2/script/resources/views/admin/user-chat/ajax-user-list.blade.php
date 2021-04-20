@forelse($userLists as $userList)

    <li id="dp_{{$userList->id}}" class="bg-white">
        <a href="javascript:void(0)" id="dpa_{{$userList->id}}"
           class="@if(isset($userID) && $userID == $userList->id) active @endif"
           onclick='getChatData("{{$userList->id}}", "{{$userList->name}}")'>

            <img src="@if(is_null($userList->image)) {{asset('img/default-profile-3.png')}} @else {{ asset_url('avatar/'.$userList->image)}} @endif"
                 alt="user-img" class="img-circle">
            <span @if($userList->message_seen == 'no' && $userList->user_one != $user->id) class="font-bold" @endif>{{$userList->name}}
                <small class="text-simple">@if($userList->last_message){{  \Carbon\Carbon::parse($userList->last_message)->diffForHumans()}} @endif
                    @if(\App\User::isAdmin($userList->id))
                        <label class="btn btn-danger btn-xs btn-outline">@lang('app.admin')</label>
                    @elseif(\App\User::isClient($userList->id))
                        <label class="btn btn-success btn-xs btn-outline">@lang('app.client')</label>
                    @else
                        <label class="btn btn-warning btn-xs btn-outline">@lang('app.employee')</label>
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