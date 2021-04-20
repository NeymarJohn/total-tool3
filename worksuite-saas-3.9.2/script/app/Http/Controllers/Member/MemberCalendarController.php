<?php

namespace App\Http\Controllers\Member;

use App\Task;
use App\TaskboardColumn;

class MemberCalendarController extends MemberBaseController
{
    public function __construct() {
        parent::__construct();
        $this->pageTitle = 'app.menu.taskCalendar';
        $this->pageIcon = 'icon-calender';
        $this->middleware(function ($request, $next) {
            if(!in_array('tasks',$this->user->modules)){
                abort(403);
            }
            return $next($request);
        });

    }

    public function index() {
        $this->tasks = Task::select('tasks.*')->join('task_users', 'task_users.task_id', '=', 'tasks.id')->where('status', 'incomplete');
        $taskBoardColumn = TaskboardColumn::where('slug', 'incomplete')->first();
        $this->tasks = Task::with('board_column')->join('task_users', 'task_users.task_id', '=', 'tasks.id')->select('tasks.*')->where('tasks.board_column_id', $taskBoardColumn->id);
        if (!$this->user->cans('view_tasks')) {
            $this->tasks = $this->tasks->where('task_users.user_id', $this->user->id);
        }
        $this->upload = can_upload();
        $this->tasks =  $this->tasks->get();
        return view('member.task-calendar.index', $this->data);
    }

    public function show($id) {
        $this->task = Task::findOrFail($id);
        return view('member.task-calendar.show', $this->data);
    }
}
