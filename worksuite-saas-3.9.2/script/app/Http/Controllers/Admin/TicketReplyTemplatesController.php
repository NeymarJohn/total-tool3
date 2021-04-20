<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Reply;
use App\Http\Requests\TicketReplyTemplate\StoreTemplate;
use App\Http\Requests\TicketReplyTemplate\UpdateTemplate;
use App\TicketReplyTemplate;
use Illuminate\Http\Request;

class TicketReplyTemplatesController extends AdminBaseController
{

    public function __construct() {
        parent::__construct();
        $this->pageTitle = 'app.menu.replyTemplates';
        $this->pageIcon = 'ti-settings';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->templates = TicketReplyTemplate::all();
        return view('admin.ticket-settings.reply-templates.index', $this->data);
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
    public function store(StoreTemplate $request)
    {
        $template = new TicketReplyTemplate();
        $template->reply_heading = $request->reply_heading;
        $template->reply_text = $request->reply_text;
        $template->save();

        return Reply::success(__('messages.templateAddSuccess'));
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
        $this->template = TicketReplyTemplate::findOrFail($id);
        return view('admin.ticket-settings.reply-templates.edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTemplate $request, $id)
    {
        $template = TicketReplyTemplate::findOrFail($id);
        $template->reply_heading = $request->reply_heading;
        $template->reply_text = $request->reply_text;
        $template->save();

        return Reply::success(__('messages.templateUpdateSuccess'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        TicketReplyTemplate::destroy($id);
        return Reply::success(__('messages.templateDeleteSuccess'));
    }


    public function fetchTemplate(Request $request){
        $templateId = $request->templateId;
        $template = TicketReplyTemplate::findOrFail($templateId);
        return Reply::dataOnly(['replyText' => $template->reply_text, 'status' => 'success']);
    }

}
