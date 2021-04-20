<?php

namespace App\Http\Controllers\Client;

use App\EmployeeDetails;
use App\Helper\Reply;
use App\Http\Controllers\Client\ClientBaseController;
use App\Http\Requests\ChatStoreRequest;
use App\Http\Requests\Message\ClientChatStore;
use App\Http\Requests\User\UpdateProfile;
use App\Http\Requests\UserChat\DeleteRequest;
use App\MessageSetting;
use App\ModuleSetting;
use App\Notifications\NewChat;
use App\Project;
use App\User;
use App\UserChat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;

/**
 * Class MemberChatController
 * @package App\Http\Controllers\Member
 */
class ClientChatController extends ClientBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.messages';
        $this->pageIcon = 'icon-envelope';
        $this->middleware(function ($request, $next) {
            if (!in_array('messages', $this->user->modules)) {
                abort(403);
            }
            return $next($request);
        });
    }

    public function index()
    {
        if ($this->messageSetting->allow_client_admin == 'no' && $this->messageSetting->allow_client_employee == 'no') {
            abort(403);
        }

        $this->userList = $this->userListLatest();

        $userID = \request('userID');
        $id     = $userID;
        $name   = '';

        if (count($this->userList) != 0) {
            if (($userID == '' || $userID == null)) {
                $id   = $this->userList[0]->id;
                $name = $this->userList[0]->name;
            } else {
                $id = $userID;
                $name = User::findOrFail($userID)->name;
            }

            $updateData = ['message_seen' => 'yes'];
            UserChat::messageSeenUpdate($this->user->id, $id, $updateData);
        }

        $this->dpData = $id;
        $this->dpName = $name;

        $this->chatDetails = UserChat::chatDetail($id, $this->user->id);

        if (request()->ajax()) {
            return $this->userChatData($this->chatDetails, 'user');
        }

        return view('client.user-chat.index', $this->data);
    }

    /**
     * @param $chatDetails
     * @param $type
     * @return string
     */
    public function userChatData($chatDetails)
    {
        $chatMessage = '';

        $this->chatDetails = $chatDetails;

        $chatMessage .= view('client.user-chat.ajax-chat-list', $this->data)->render();

        $chatMessage .= '<li id="scrollHere"></li>';

        return Reply::successWithData(__('messages.fetchChat'), ['chatData' => $chatMessage]);
    }

    /**
     * @return mixed
     */
    public function postChatMessage(ClientChatStore $request)
    {
        $this->user = auth()->user();

        $message = $request->get('message');

        if ($request->user_type == 'admin') {
            $userID = $request->get('admin_id');
        } else {
            $userID = $request->get('user_id');
        }

        $allocatedModel = new UserChat();
        $allocatedModel->message         = $message;
        $allocatedModel->user_one        = $this->user->id;
        $allocatedModel->user_id         = $userID;
        $allocatedModel->from            = $this->user->id;
        $allocatedModel->to              = $userID;
        $allocatedModel->save();

        $this->userLists = $this->userListLatest();

        $this->userID = $userID;

        $users = view('admin.user-chat.ajax-user-list', $this->data)->render();

        $lastLiID = '';
        return Reply::successWithData(__('messages.fetchChat'), ['chatData' => $this->index(), 'dataUserID' => $this->user->id, 'userList' => $users, 'liID' => $lastLiID]);
    }

    /**
     * @return mixed
     */
    public function userListLatest($term = null)
    {
        $result = User::userListLatest($this->user->id, $term);

        return $result;
    }

    public function getUserSearch(Request $request)
    {
        $term = $request->get('term');
        $this->userLists = $this->userListLatest($term);

        $users = '';

        $users = view('client.user-chat.ajax-user-list', $this->data)->render();

        return Reply::dataOnly(['userList' => $users]);
    }

    public function create()
    {
        $this->members = User::join('project_members', 'project_members.user_id', '=', 'users.id')
            ->join('projects', 'projects.id', '=', 'project_members.project_id')
            ->where('projects.client_id', $this->user->id)
            ->select('users.id', 'users.name')
            ->get();
        $this->admins = User::allAdmins();
        return view('client.user-chat.create', $this->data);
    }

    public function destroy($id)
    {
        UserChat::destroy($id);

        return Reply::success(__('messages.deleteSuccess'));
    }
}
