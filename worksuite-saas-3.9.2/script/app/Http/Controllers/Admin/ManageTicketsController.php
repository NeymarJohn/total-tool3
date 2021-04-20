<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\TicketDataTable;
use App\Helper\Files;
use App\Helper\Reply;
use App\Http\Requests\Tickets\StoreTicket;
use App\Http\Requests\Tickets\UpdateTicket;
use App\Notifications\ReplyTicket;
use App\Ticket;
use App\TicketChannel;
use App\TicketFile;
use App\TicketGroup;
use App\TicketReply;
use App\TicketReplyTemplate;
use App\TicketTag;
use App\TicketTagList;
use App\TicketType;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ManageTicketsController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.tickets';
        $this->pageIcon = 'ti-ticket';
        $this->middleware(function ($request, $next) {
            abort_if(!in_array('tickets', $this->user->modules),403);
            return $next($request);
        });
    }

    public function index(TicketDataTable $dataTable)
    {
        $this->channels  = TicketChannel::all();
        $this->groups    = TicketGroup::all();
        $this->types     = TicketType::all();

        $this->chartData = $this->getGraphData();

        return $dataTable->render('admin.tickets.index', $this->data);
    }

    public function getGraphData($fromDate = null, $toDate = null, $agentId = null, $status = 'open', $priority = null, $channelId = null, $typeId = null)
    {
        $graphData  = [];
        $resolved   = [];
        $pending    = [];
        $open       = [];
        $closed     = [];

        $totalTickets = Ticket::with('reply')
            ->selectRaw('DATE_FORMAT(created_at,"%Y-%m-%d") as date, count(id) as total, status')
            ->groupBy('created_at')
            ->orderBy('created_at', 'ASC');

        if ($fromDate) {
            $startDate = Carbon::createFromFormat($this->global->date_format, $fromDate)->toDateString();
            $totalTickets->where(DB::raw('DATE(`created_at`)'), '>=', $startDate);
        }
        if ($toDate) {
            $endDate = Carbon::createFromFormat($this->global->date_format, $toDate)->toDateString();
            $totalTickets->where(DB::raw('DATE(`created_at`)'), '<=', $endDate);
        }

        if (!is_null($agentId)  && $agentId != 'all') {
            $totalTickets->where('agent_id', '=', $agentId);
        }

        if (!is_null($status)  && $status != 'all') {
            $totalTickets->where('status', '=', $status);
        }

        if (!is_null($priority)  && $priority != 'all') {
            $totalTickets->where('priority', '=', $priority);
        }

        if (!is_null($channelId)   && $channelId != 'all') {
            $totalTickets->where('channel_id', '=', $channelId);
        }

        if (!is_null($typeId)   && $typeId != 'all') {
            $totalTickets->where('type_id', '=', $typeId);
        }

        $totalTickets = $totalTickets->get();

        $total = $totalTickets->countBy('date')->toArray();

        //Pending Ticket Data
        if($status == 'pending' ||  $status == 'all'){
            $pending = $totalTickets->filter(function ($value, $key) {
                return $value->status == 'pending';
            })->countBy('date')->toArray();
        }

        //Open Ticket Data
        if($status == 'open' ||  $status == 'all') {
            $open = $totalTickets->filter(function ($value, $key) {
                return $value->status == 'open';
            })->countBy('date')->toArray();
        }

        //Resolved Ticket Data
        if($status == 'resolved' ||  $status == 'all') {
            $resolved = $totalTickets->filter(function ($value, $key) {
                return $value->status == 'resolved';
            })->countBy('date')->toArray();
        }

        //Closed Ticket Data
        if($status == 'closed' ||  $status == 'all') {
            $closed = $totalTickets->filter(function ($value, $key) {
                return $value->status == 'closed';
            })->countBy('date')->toArray();

        }

        $allRecords = array_merge($total, $resolved, $open, $closed, $pending);
        $dates = array_keys($allRecords);

        foreach ($dates as $date) {
            $graphData[] = [
                'date'      =>  $date,
                'total'     =>  isset($total[$date]) ? $total[$date] : 0,
                'resolved'  =>  isset($resolved[$date]) ? $resolved[$date] : 0,
                'open'      =>  isset($open[$date]) ? $open[$date] : 0,
                'closed'    =>  isset($closed[$date]) ? $closed[$date] : 0,
                'pending'   =>  isset($pending[$date]) ? $pending[$date] : 0
            ];
        }

        usort($graphData, function ($a, $b) {
            $t1 = strtotime($a['date']);
            $t2 = strtotime($b['date']);
            return $t1 - $t2;
        });

        return $graphData;
    }


    public function create()
    {
        $this->groups = TicketGroup::all();
        $this->types = TicketType::all();
        $this->channels = TicketChannel::all();
        $this->templates = TicketReplyTemplate::all();
        $this->requesters = User::all();
        $this->lastTicket = Ticket::orderBy('id', 'desc')->first();
        $this->upload = can_upload();
        return view('admin.tickets.create', $this->data);
    }

    public function store(StoreTicket $request)
    {
        $ticket = new Ticket();
        $ticket->subject = $request->subject;
        $ticket->status = $request->status;
        $ticket->user_id = $request->user_id;
        $ticket->agent_id = $request->agent_id;
        $ticket->type_id = $request->type_id;
        $ticket->priority = $request->priority;
        $ticket->channel_id = $request->channel_id;
        $ticket->save();

        //save first message
        $reply = new TicketReply();
        $reply->message = $request->description;
        $reply->ticket_id = $ticket->id;
        $reply->user_id = $this->user->id; //current logged in user
        $reply->save();

        // save tags
        $tags = $request->tags;

        if ($tags) {
            TicketTag::where('ticket_id', $ticket->id)->delete();
            foreach ($tags as $tag) {
                $tag = TicketTagList::firstOrCreate([
                    'tag_name' => $tag
                ]);


                TicketTag::create([
                    'tag_id' => $tag->id,
                    'ticket_id' => $ticket->id
                ]);
            }
        }

        //log search
        $this->logSearchEntry($ticket->id, 'Ticket: ' . $ticket->subject, 'admin.tickets.edit', 'ticket');

        return Reply::dataOnly(['ticketReplyID' => $reply->id]);

        //        return Reply::redirect(route('admin.tickets.index'), __('messages.ticketAddSuccess'));
    }

    public function edit($id)
    {
        $this->ticket       = Ticket::findOrFail($id);

        $this->groups       = TicketGroup::all();
        $this->types        = TicketType::all();
        $this->channels     = TicketChannel::all();
        $this->templates    = TicketReplyTemplate::all();
        $this->upload       = can_upload();
        return view('admin.tickets.edit', $this->data);
    }

    public function update(UpdateTicket $request, $id)
    {
        // DB::beginTransaction();
        $ticket = Ticket::findOrFail($id);
        $ticket->status = $request->status;
        // $ticket->agent_id = $request->agent_id;
        // $ticket->type_id = $request->type_id;
        // $ticket->priority = $request->priority;
        // $ticket->channel_id = $request->channel_id;
        $ticket->save();

        $lastMessage = null;

        if ($request->message != '') {
            //save first message
            $reply = new TicketReply();
            $reply->message = $request->message;
            $reply->ticket_id = $ticket->id;
            $reply->user_id = $this->user->id; //current logged in user
            $reply->save();

            //$this->fileSave($request, $reply->id);

            $global = $this->global;

            $lastMessage = view('admin.tickets.last-message', compact('reply', 'global'))->render();
            return Reply::dataOnly(['lastMessage' => $lastMessage, 'ticketReplyID' => $reply->id]);
        }

        // DB::commit();
        return Reply::successWithData(__('messages.ticketReplySuccess'), ['lastMessage' => $lastMessage]);
    }

    public function updateOtherData(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->agent_id = $request->agent_id;
        $ticket->type_id = $request->type_id;
        $ticket->priority = $request->priority;
        $ticket->channel_id = $request->channel_id;
        $ticket->save();

        $tags = $request->tags;
        if ($tags) {
            TicketTag::where('ticket_id', $ticket->id)->delete();

            foreach ($tags as $tag) {
                $tag = TicketTagList::firstOrCreate([
                    'tag_name' => $tag
                ]);


                TicketTag::create([
                    'tag_id' => $tag->id,
                    'ticket_id' => $ticket->id
                ]);
            }
        }
        else{
            TicketTag::where('ticket_id', $ticket->id)->delete();
        }
        return Reply::redirect(route('admin.tickets.index'), __('messages.updateSuccess'));
    }

    public function destroy($id)
    {
        Ticket::destroy($id);
        return Reply::success(__('messages.ticketDeleteSuccess'));
    }

    public function destroyReply($id)
    {
        $ticketFiles = TicketFile::where('ticket_reply_id', $id)->get();
        foreach ($ticketFiles as $file) {
            Files::deleteFile($file->hashname, 'ticket-files/' . $file->ticket_reply_id);
            $file->delete();
        }
        TicketReply::destroy($id);
        return Reply::success(__('messages.ticketDeleteSuccess'));
    }

    public function refreshCount(Request $request)
    {
        $tickets = Ticket::with('agent');

        if ($request->startDate) {
            $startDate = Carbon::createFromFormat($this->global->date_format, $request->startDate)->toDateString();
            $tickets->where(DB::raw('DATE(`created_at`)'), '>=', $startDate);
        }
        if ($request->endDate) {
            $endDate = Carbon::createFromFormat($this->global->date_format, $request->endDate)->toDateString();
            $tickets->where(DB::raw('DATE(`created_at`)'), '<=', $endDate);
        }

        if (!is_null($request->agentId)  && $request->agentId != 'all') {
            $tickets->where('agent_id', '=', $request->agentId);
        }

        if (!is_null($request->status) && $request->status != 'all') {
            $tickets->where('status', '=', $request->status);
        }

        if (!is_null($request->priority)  && $request->priority != 'all') {
            $tickets->where('priority', '=', $request->priority);
        }

        if (!is_null($request->channelId)  && $request->channelId != 'all') {
            $tickets->where('channel_id', '=', $request->channelId);
        }

        if (!is_null($request->typeId)  && $request->typeId != 'all') {
            $tickets->where('type_id', '=', $request->typeId);
        }

        $tickets = $tickets->get();

        $openTickets = $tickets->filter(function ($value, $key) {
            return $value->status == 'open';
        })->count();

        $pendingTickets = $tickets->filter(function ($value, $key) {
            return $value->status == 'pending';
        })->count();


        $resolvedTickets = $tickets->filter(function ($value, $key) {
            return $value->status == 'resolved';
        })->count();

        $closedTickets = $tickets->filter(function ($value, $key) {
            return $value->status == 'closed';
        })->count();


        $totalTickets = $tickets->count();

        $chartData = $this->getGraphData($request->startDate, $request->endDate, $request->agentId, $request->status, $request->priority, $request->channelId, $request->typeId);

        $chartData = json_encode($chartData);

        $ticketData = [
            'chartData'         => $chartData,
            'totalTickets'      => $totalTickets,
            'closedTickets'     => $closedTickets,
            'openTickets'       => $openTickets,
            'pendingTickets'    => $pendingTickets,
            'resolvedTickets'   => $resolvedTickets];

        return Reply::dataOnly($ticketData);
    }

    public function export($startDate = null, $endDate = null, $agentId = null, $status = null, $priority = null, $channelId = null, $typeId = null)
    {

        $tickets = Ticket::join('users', 'users.id', 'tickets.user_id')
            ->select('tickets.id', 'tickets.subject', 'users.name', 'tickets.created_at', 'tickets.status');

        if ($startDate) {
            $tickets->where(DB::raw('DATE(tickets.created_at)'), '>=', $startDate);
        }

        if ($endDate) {
            $tickets->where(DB::raw('DATE(tickets.created_at)'), '<=', $endDate);
        }

        if ($agentId) {
            $tickets->where('tickets.agent_id', '=', $agentId);
        }

        if ($status) {
            $tickets->where('tickets.status', '=', $status);
        }

        if ($priority) {
            $tickets->where('tickets.priority', '=', $priority);
        }

        if ($channelId) {
            $tickets->where('tickets.channel_id', '=', $channelId);
        }

        if ($typeId) {
            $tickets->where('tickets.type_id', '=', $typeId);
        }

        $attributes =  ['created_at'];

        $tickets = $tickets->get()->makeHidden($attributes);

        // Initialize the array which will be passed into the Excel
        // generator.
        $exportArray = [];

        // Define the Excel spreadsheet headers
        $exportArray[] = ['ID', 'Subject', 'Requested Name', 'Status', 'Requested On'];

        // Convert each member of the returned collection into an array,
        // and append it to the payments array.
        foreach ($tickets as $row) {
            $exportArray[] = $row->toArray();
        }

        // Generate and return the spreadsheet
        Excel::create('Ticket', function ($excel) use ($exportArray) {

            // Set the spreadsheet title, creator, and description
            $excel->setTitle('Ticket');
            $excel->setCreator('Worksuite')->setCompany($this->companyName);
            $excel->setDescription('Ticket file');

            // Build the spreadsheet, passing in the payments array
            $excel->sheet('sheet1', function ($sheet) use ($exportArray) {
                $sheet->fromArray($exportArray, null, 'A1', false, false);

                $sheet->row(1, function ($row) {

                    // call row manipulation methods
                    $row->setFont(array(
                        'bold'       =>  true
                    ));
                });
            });
        })->download('xlsx');
    }
}
