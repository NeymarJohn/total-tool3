<?php

namespace App\Http\Controllers\Client;

use App\Helper\Reply;
use App\Http\Requests\Tasks\StoreTaskComment;
use App\TaskComment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ClientTaskCommentController extends ClientBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTaskComment $request)
    {
        $comment = new TaskComment();
        $comment->comment = $request->comment;
        $comment->task_id = $request->taskId;
        $comment->user_id = $this->user->id;
        $comment->save();

        $this->comments = TaskComment::where('task_id', $request->taskId)->orderBy('id', 'desc')->get();
        $view = view('client.tasks.task_comment', $this->data)->render();

        return Reply::dataOnly(['status' => 'success', 'view' => $view]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $comment = TaskComment::findOrFail($id);
        $comment->delete();
        $this->comments = TaskComment::where('task_id', $comment->task_id)->orderBy('id', 'desc')->get();
        $view = view('client.tasks.task_comment', $this->data)->render();

        return Reply::dataOnly(['status' => 'success', 'view' => $view]);
    }
}
