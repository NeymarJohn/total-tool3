<?php

namespace App\Observers;

use App\ClientDetails;
use App\Events\NewNoticeEvent;
use App\Http\Controllers\Admin\AdminBaseController;
use App\Notice;
use App\NoticeView;
use App\UniversalSearch;
use App\User;

class NoticeObserver
{

    public function created(Notice $notice)
    {
        if (!isRunningInConsoleOrSeeding()) {
            $this->sendNotification($notice);
        }
        $log = new AdminBaseController();
        $log->logSearchEntry($notice->id, 'Notice: ' . $notice->heading, 'admin.notices.edit', 'notice');
    }

    public function updated(Notice $notice)
    {
        if (!isRunningInConsoleOrSeeding()) {
            $this->sendNotification($notice, 'update');
        }
    }

    public function deleting(Notice $notice)
    {
        $universalSearches = UniversalSearch::where('searchable_id', $notice->id)->where('module_type', 'notice')->get();
        if ($universalSearches) {
            foreach ($universalSearches as $universalSearch) {
                UniversalSearch::destroy($universalSearch->id);
            }
        }
    }

    public function sendNotification($notice, $action = 'create')
    {
        if ($notice->to == 'employee') {
            if (request()->team_id != '') {
                $users = User::join('employee_details', 'employee_details.user_id', '=', 'users.id')
                    ->where('employee_details.department_id', request()->team_id)
                    ->select('users.id', 'users.name', 'users.email', 'users.created_at', 'users.image')
                    ->get();
            } else {
                $users = User::allEmployees();
            }


            foreach($users as $userData){
                NoticeView::updateOrCreate(array(
                    'user_id' => $userData->id,
                    'notice_id' => $notice->id
                ));
            }

            event(new NewNoticeEvent($notice, $users, $action));
        }
        if ($notice->to == 'client') {

            $users = User::allClients();

            foreach($users as $userData){
                NoticeView::updateOrCreate(array(
                    'user_id' => $userData->id,
                    'notice_id' => $notice->id
                ));
            }

            event(new NewNoticeEvent($notice, $users, $action));
        }
    }
}
