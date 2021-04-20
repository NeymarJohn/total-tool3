<div class="panel panel-default">
    <div class="panel-heading "><i class="icon-note"></i> {{ count($user->unreadNotifications) }} @lang('app.unreadNotifications')
        <div class="panel-action">
            <a href="javascript:;" class="close" data-dismiss="modal"><i class="ti-close"></i></a>
        </div>
    </div>
    <div class="panel-wrapper collapse in">
        <div class="panel-body">
            <div class="col-xs-12">
                @foreach ($user->unreadNotifications as $notification)
                    @if(view()->exists('notifications.client.'.\Illuminate\Support\Str::snake(class_basename($notification->type))))
                        @include('notifications.client.detail_'.snake_case(class_basename($notification->type)))
                    @endif
                @endforeach
            </div>

        </div>
    </div>
</div>
