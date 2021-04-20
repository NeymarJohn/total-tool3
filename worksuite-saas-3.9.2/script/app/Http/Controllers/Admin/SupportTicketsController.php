<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\SupportTicketDataTable;
use App\Helper\Files;
use App\Helper\Reply;
use App\Http\Requests\SupportTickets\StoreRequest;
use App\Http\Requests\Tickets\UpdateTicket;
use App\SupportTicket;
use App\SupportTicketFile;
use App\SupportTicketReply;
use App\SupportTicketType;
use App\TicketType;
use App\User;
use Illuminate\Http\Request;

class SupportTicketsController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.supportTicket';
        $this->pageIcon = 'ti-ticket';
        $this->middleware(function ($request, $next) {
            if (!in_array('ticket support', $this->user->modules)) {
                abort(403);
            }
            return $next($request);
        });
    }

    public function index(SupportTicketDataTable $dataTable)
    {
        $this->types        = SupportTicketType::all();
        $this->superadmins  = User::allSuperAdmin();

        return $dataTable->render('admin.support-tickets.index', $this->data);
    }

    public function create()
    {
        $this->types        = SupportTicketType::all();
        $this->superadmins  = User::allSuperAdmin();
        $this->lastTicket   = SupportTicket::orderBy('id', 'desc')->first();
        $this->upload       = can_upload();

        return view('admin.support-tickets.create', $this->data);
    }

    public function store(StoreRequest $request)
    {

        $ticket = new SupportTicket();
        $ticket->subject                = $request->subject;
        $ticket->status                 = $request->status;
        $ticket->user_id                = $this->user->id;
        $ticket->created_by             = $this->user->id;
        $ticket->agent_id               = $request->agent_id;
        if(is_null($request->agent_id)){
            $superadmin = User::where('super_admin', '1')->first();
            $ticket->agent_id           = $superadmin->id;
        }

        $ticket->support_ticket_type_id = $request->type_id;
        $ticket->priority               = $request->priority;
        $ticket->save();

        //save first message
        $reply = new SupportTicketReply();
        $reply->message             = $request->description;
        $reply->support_ticket_id   = $ticket->id;
        $reply->user_id             = $this->user->id; //current logged in user
        $reply->save();

        return Reply::dataOnly(['ticketReplyID' => $reply->id]);
    }

    public function edit($id)
    {

        $this->ticket       = SupportTicket::with(['reply', 'reply.user'])->findOrFail($id);
        $this->types        = SupportTicketType::all();
        $this->upload       = can_upload();
        $this->superadmins  = User::allSuperAdmin();
        return view('admin.support-tickets.edit', $this->data);
    }

    public function update(UpdateTicket $request, $id)
    {
        // DB::beginTransaction();
        $ticket = SupportTicket::findOrFail($id);
        $ticket->status = $request->status;
        $ticket->save();

        $lastMessage = null;

        if ($request->message != '') {
            //save first message
            $reply = new SupportTicketReply();
            $reply->message             = $request->message;
            $reply->support_ticket_id   = $ticket->id;
            $reply->user_id             = $this->user->id; //current logged in user
            $reply->save();

            //$this->fileSave($request, $reply->id);

            $global = $this->global;

            $lastMessage = view('admin.support-tickets.last-message', compact('reply', 'global'))->render();
            return Reply::dataOnly(['lastMessage' => $lastMessage, 'ticketReplyID' => $reply->id]);
        }

        // DB::commit();
        return Reply::successWithData(__('messages.ticketReplySuccess'), ['lastMessage' => $lastMessage]);
    }

    public function updateOtherData(Request $request, $id)
    {
        $ticket = SupportTicket::findOrFail($id);
        $ticket->agent_id               = $request->agent_id;
        $ticket->support_ticket_type_id = $request->type_id;
        $ticket->priority               = $request->priority;
        $ticket->save();
        return Reply::redirect(route('admin.support-tickets.index'), __('messages.updateSuccess'));
    }

    public function destroy($id)
    {
        SupportTicket::destroy($id);
        return Reply::success(__('messages.ticketDeleteSuccess'));
    }

    public function destroyReply($id)
    {
        $ticketFiles = SupportTicketFile::where('support_ticket_reply_id', $id)->get();
        foreach ($ticketFiles as $file) {
            Files::deleteFile($file->hashname, 'support-ticket-files/' . $file->support_ticket_reply_id);
            $file->delete();
        }
        SupportTicketReply::destroy($id);
        return Reply::success(__('messages.ticketDeleteSuccess'));
    }
}
