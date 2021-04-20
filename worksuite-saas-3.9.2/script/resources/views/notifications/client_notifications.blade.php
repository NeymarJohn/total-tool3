<li>
    <div class="drop-title row">
        <span class="font-12 col-xs-6 font-semi-bold">@lang('app.newNotifications')</span>
        <a class="mark-notification-read col-xs-6 text-right font-12 font-semi-bold"
            href="javascript:;"> @lang('app.markRead')</a>
    </div>
</li>
@forelse ($user->unreadNotifications as $notification)
    @if(view()->exists('notifications.client.'.\Illuminate\Support\Str::snake(class_basename($notification->type))))
        @include('notifications.client.'.\Illuminate\Support\Str::snake(class_basename($notification->type)))
    @endif
@empty
    <li class="p-10">@lang('messages.noNotification')</li>
@endforelse