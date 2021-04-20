<div class="panel panel-default">
    <div class="panel-heading "><i class="icon-note"></i> {{ count($user->unreadNotifications) }} Unread Notifications
        <div class="panel-action">
            <a href="javascript:;" class="close" data-dismiss="modal"><i class="ti-close"></i></a>
        </div>
    </div>
    <div class="panel-wrapper collapse in">
        <div class="panel-body">
            <div class="col-md-12">
                @foreach ($user->unreadNotifications as $notification)
                    @include('notifications.client.detail_'.\Illuminate\Support\Str::snake(class_basename($notification->type)))
                @endforeach
            </div>

        </div>
    </div>
</div>
