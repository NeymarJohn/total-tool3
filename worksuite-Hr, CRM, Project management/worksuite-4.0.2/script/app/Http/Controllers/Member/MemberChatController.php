<?php

namespace App\Http\Controllers\Member;

use App\Helper\Reply;
use App\Http\Requests\ChatStoreRequest;
use App\MessageSetting;
use App\Notifications\NewChat;
use App\User;
use App\UserChat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

/**
 * Class MemberChatController
 * @package App\Http\Controllers\Member
 */
class MemberChatController extends MemberBaseController
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

        return view('member.user-chat.index', $this->data);
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

        $chatMessage .= view('member.user-chat.ajax-chat-list', $this->data)->render();

        $chatMessage .= '<li id="scrollHere"></li>';

        return Reply::successWithData(__('messages.fetchChat'), ['chatData' => $chatMessage]);
    }

    /**
     * @return mixed
     */
    public function postChatMessage(ChatStoreRequest $request)
    {
        $this->user = auth()->user();

        $message          = $request->get('message');

        if ($request->user_type == 'client') {
            $userID = $request->get('client_id');
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

        $users = view('member.user-chat.ajax-user-list', $this->data)->render();

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

        $users = view('member.user-chat.ajax-user-list', $this->data)->render();

        return Reply::dataOnly(['userList' => $users]);
    }

    public function create()
    {
        $this->members = User::allEmployees($this->user->id);
        $this->clients = User::join('projects', 'projects.client_id', '=', 'users.id')
            ->join('project_members', 'project_members.project_id', '=', 'projects.id')
            ->where('project_members.user_id', $this->user->id)
            ->select('users.id', 'users.name')
            ->get();
        $this->messageSetting = message_setting();
        return view('member.user-chat.create', $this->data);
    }

    public function destroy($id)
    {
        UserChat::destroy($id);

        return Reply::success(__('messages.deleteSuccess'));
    }
}
