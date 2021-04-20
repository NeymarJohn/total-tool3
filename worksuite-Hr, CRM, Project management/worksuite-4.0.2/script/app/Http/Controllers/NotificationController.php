<?php

namespace App\Http\Controllers;

use App\Helper\Reply;
use App\Http\Controllers\Admin\AdminBaseController;
use Illuminate\Http\Request;

class NotificationController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function markAllRead()
    {
        $this->user->unreadNotifications->markAsRead();
        return Reply::success(__('messages.notificationRead'));
    }

    public function showUserNotifications()
    {
        $view = view('notifications.user_notifications', $this->data)->render();
        return Reply::dataOnly(['status' => 'success', 'html' => $view]);
    }

    public function showAdminNotifications()
    {
        $view = view('notifications.admin_user_notifications', $this->data)->render();
        return Reply::dataOnly(['status' => 'success', 'html' => $view]);
    }

    public function showClientNotifications()
    {
        $view = view('notifications.client_notifications', $this->data)->render();
        return Reply::dataOnly(['status' => 'success', 'html' => $view]);
    }

    public function showAllMemberNotifications()
    {
        return view('notifications.member.all_notifications', $this->data);
    }

    public function showAllClientNotifications()
    {
        return view('notifications.client.all_notifications', $this->data);
    }

    public function showAllAdminNotifications()
    {
        return view('notifications.admin.all_notifications', $this->data);
    }
}
