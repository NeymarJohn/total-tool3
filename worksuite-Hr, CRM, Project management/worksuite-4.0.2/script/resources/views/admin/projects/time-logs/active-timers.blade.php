@forelse($activeTimers as $key=>$time)
    <tr>
        <td>{{ $key+1 }}</td>
        <td>{{ ucwords($time->user->name) }}</td>
        <td class="font-bold timer">{{ $time->duration }}</td>
        <td><a href="javascript:;" data-time-id="{{ $time->id }}" class="label label-danger stop-timer">STOP</a></td>
    </tr>
@empty
    <tr>
        <td colspan="6">@lang('messages.noActiveTimer')</td>
    </tr>
@endforelse
