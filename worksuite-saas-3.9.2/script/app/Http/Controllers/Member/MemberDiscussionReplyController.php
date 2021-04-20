<?php

namespace App\Http\Controllers\Member;

use App\Discussion;
use App\DiscussionReply;
use App\Helper\Reply;
use App\Http\Controllers\Controller;
use App\Http\Requests\DiscussionReply\StoreRequest;
use Illuminate\Http\Request;

class MemberDiscussionReplyController extends MemberBaseController
{

    public function __construct()
    {
        parent::__construct();
    }
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
        $this->discussionId = request('id');
        return view('member.discussion-reply.create', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $reply = new DiscussionReply();
        $reply->user_id = $this->user->id;
        $reply->discussion_id = $request->discussion_id;
        $reply->body = $request->description;
        $reply->save();

        return $this->ajaxDiscussionReplies($reply->discussion_id);
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
        $this->reply = DiscussionReply::findOrFail($id);
        return view('member.discussion-reply.edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreRequest $request, $id)
    {
        $reply = DiscussionReply::findOrFail($id);
        $reply->body = $request->description;
        $reply->save();

        return $this->ajaxDiscussionReplies($reply->discussion_id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $reply = DiscussionReply::findOrFail($id);
        $reply->delete();

        return $this->ajaxDiscussionReplies($reply->discussion_id);    
    }

    /**
     * send ajax replies
     * 
     * @param int $discussionId
     *
     * @return \Illuminate\Http\Response
     */
    public function ajaxDiscussionReplies($discussionId)
    {
        $this->discussionReplies = DiscussionReply::with('user')->where('discussion_id', $discussionId)->orderBy('id', 'asc')->get();
        $this->discussion = Discussion::with('category')->findOrFail($discussionId);

        $html = view('member.discussion-reply.reply', $this->data)->render();
        return Reply::dataOnly(['status' => 'success', 'html' => $html]);
    }
}
