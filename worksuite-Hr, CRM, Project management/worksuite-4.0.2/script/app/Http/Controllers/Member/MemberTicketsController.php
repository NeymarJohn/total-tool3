<?php

namespace App\Http\Controllers\Member;

use App\Helper\Files;
use App\Helper\Reply;
use App\Http\Requests\Tickets\StoreTicket;
use App\Http\Requests\Tickets\StoreTicketRequest;
use App\Http\Requests\Tickets\UpdateTicket;
use App\Http\Requests\Tickets\UpdateTicketRequest;
use App\Notifications\NewTicket;
use App\Ticket;
use App\TicketAgentGroups;
use App\TicketChannel;
use App\TicketFile;
use App\TicketGroup;
use App\TicketReply;
use App\TicketReplyTemplate;
use App\TicketTag;
use App\TicketTagList;
use App\TicketType;
use App\User;
use App\Country;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Yajra\DataTables\Facades\DataTables;

class MemberTicketsController extends MemberBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.tickets';
        $this->pageIcon = 'ti-ticket';
        $this->middleware(function ($request, $next) {
            if (!in_array('tickets', $this->user->modules)) {
                abort(403);
            }
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->isAgent = TicketAgentGroups::where('agent_id', $this->user->id)->first();

        if ($this->user->can('view_tickets')) {
            $this->startDate = Carbon::today()->subWeek(1)->timezone($this->global->timezone)->format('m/d/Y');
            $this->endDate = Carbon::today()->timezone($this->global->timezone)->format('m/d/Y');
            $this->channels = TicketChannel::all();
            $this->groups = TicketGroup::all();
            $this->types = TicketType::all();
            return view('member.tickets.index-admin', $this->data);
        }
        return view('member.tickets.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if ($this->user->can('add_tickets')) {
            $this->groups = TicketGroup::all();
            $this->types = TicketType::all();
            $this->channels = TicketChannel::all();
            $this->templates = TicketReplyTemplate::all();
            $this->requesters = User::all();
            $this->lastTicket = Ticket::orderBy('id', 'desc')->first();
            return view('member.tickets.create-admin', $this->data);
        }
        $this->countries = Country::all();
        return view('member.tickets.create', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTicketRequest $request)
    {
        $ticket = new Ticket();
        $ticket->subject = $request->subject;
        $ticket->user_id = $this->user->id;
        $ticket->country_id  = $request->phone_code;
        $ticket->mobile = $request->mobile;
        $ticket->save();

        //save first message
        $reply = new TicketReply();
        $reply->message = $request->description;
        $reply->ticket_id = $ticket->id;
        $reply->user_id = $this->user->id; //current logged in user
        $reply->save();
        $this->fileSave($request, $reply->id);

        return Reply::redirect(route('member.tickets.index'), __('messages.ticketAddSuccess'));
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
        if ($this->user->can('add_tickets')) {
            $this->ticket = Ticket::findOrFail($id);
            $this->groups = TicketGroup::all();
            $this->types = TicketType::all();
            $this->channels = TicketChannel::all();
            $this->templates = TicketReplyTemplate::all();
            return view('member.tickets.edit-admin', $this->data);
        }
        $this->ticket = Ticket::findOrFail($id);
        if ($this->ticket->agent_id == $this->user->id || $this->ticket->user_id == $this->user->id) {
            return view('member.tickets.edit', $this->data);
        }
        abort(403);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTicketRequest $request, $id)
    {
        $reply = new TicketReply();
        $reply->message = $request->message;
        $reply->ticket_id = $id;
        $reply->user_id = $this->user->id; //current logged in user
        $reply->save();

        $this->fileSave($request, $reply->id);

        $this->reply = $reply;
        $lastMessage = view('member.tickets.last-message', $this->data)->render();

        return Reply::successWithData(__('messages.ticketReplySuccess'), ['lastMessage' => $lastMessage]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Ticket::destroy($id);
        return Reply::success(__('messages.ticketDeleteSuccess'));
    }

    public function data(Request $request)
    {
        $tickets = Ticket::where('user_id', $this->user->id);

        if ($request->startDate && $request->startDate != '') {
            $startDate = Carbon::createFromFormat($this->global->date_format, $request->startDate)->toDateString();
            $tickets->where(DB::raw('DATE(tickets.created_at)'), '>=', $startDate);
        }

        if ($request->endDate && $request->endDate != '') {
            $endDate = Carbon::createFromFormat($this->global->date_format, $request->endDate)->toDateString();
            $tickets->where(DB::raw('DATE(tickets.created_at)'), '<=', $endDate);
        }

        if ($request->agentId && $request->agentId != 'all' ) {
            $tickets->where('tickets.agent_id', '=', $request->agentId);
        }

        if ($request->status && $request->status != 'all') {
            $tickets->where('tickets.status', '=', $request->status);
        }

        if ($request->priority && $request->priority != 'all' ) {
            $tickets->where('tickets.priority', '=', $request->priority);
        }

        return DataTables::of($tickets)
            ->addColumn('action', function ($row) {
                return '<a href="' . route("member.tickets.edit", $row->id) . '" class="btn btn-info btn-sm btn-outline" ><i class="fa fa-eye"></i> ' . __('modules.client.viewDetails') . '</a>';
            })
            ->editColumn('subject', function ($row) {
                return ucfirst($row->subject);
            })
            ->editColumn('agent_id', function ($row) {
                if (!is_null($row->agent_id)) {
                    return ucwords($row->agent->name);
                }
            })
            ->editColumn('status', function ($row) {
                if ($row->status == 'open') {
                    return '<label class="label label-danger">' . $row->status . '</label>';
                } elseif ($row->status == 'pending') {
                    return '<label class="label label-warning">' . $row->status . '</label>';
                } elseif ($row->status == 'resolved') {
                    return '<label class="label label-info">' . $row->status . '</label>';
                } elseif ($row->status == 'closed') {
                    return '<label class="label label-success">' . $row->status . '</label>';
                }
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at->timezone($this->global->timezone)->format($this->global->date_format);
            })
            ->editColumn('updated_at', function ($row) {
                return $row->updated_at->timezone($this->global->timezone)->format($this->global->date_format);
            })
            ->rawColumns(['action', 'status'])
            ->removeColumn('user_id')
            ->removeColumn('channel_id')
            ->removeColumn('type_id')
            ->removeColumn('deleted_at')
            ->make(true);
    }

    public function closeTicket($id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->status = 'closed';
        $ticket->save();

        $reply = new TicketReply();
        $reply->message = 'Ticket <strong>closed</strong> by ' . ucwords($this->user->name);
        $reply->ticket_id = $id;
        $reply->user_id = $this->user->id; //current logged in user
        $reply->save();

        return Reply::redirect(route('member.tickets.index'), __('messages.ticketReplySuccess'));
    }

    public function reopenTicket($id)
    {
        $ticket = Ticket::findOrFail($id);
        if (is_null($ticket->agent_id)) {
            $ticket->status = 'open';
        } else {
            $ticket->status = 'pending';
        }
        $ticket->save();

        $reply = new TicketReply();
        $reply->message = 'Ticket <strong>reopend</strong> by ' . ucwords($this->user->name);
        $reply->ticket_id = $id;
        $reply->user_id = $this->user->id; //current logged in user
        $reply->save();

        return Reply::redirect(route('member.tickets.index'), __('messages.ticketReplySuccess'));
    }

    public function adminData(Request $request)
    {
        $tickets = Ticket::select('*');

        if ($request->startDate && $request->startDate != '') {
            $startDate = Carbon::createFromFormat($this->global->date_format, $request->startDate)->toDateString();
            $tickets->where(DB::raw('DATE(tickets.created_at)'), '>=', $startDate);
        }

        if ($request->endDate && $request->endDate != '') {
            $endDate = Carbon::createFromFormat($this->global->date_format, $request->endDate)->toDateString();
            $tickets->where(DB::raw('DATE(tickets.created_at)'), '<=', $endDate);
        }

        if ($request->agentId && $request->agentId != 'all' ) {
            $tickets->where('tickets.agent_id', '=', $request->agentId);
        }

        if ($request->status && $request->status != 'all') {
            $tickets->where('tickets.status', '=', $request->status);
        }

        if ($request->priority && $request->priority != 'all' ) {
            $tickets->where('tickets.priority', '=', $request->priority);
        }

        if ($request->channelId && $request->channelId != 'all' ) {

            $tickets->where('tickets.channel_id', '=', $request->channelId);
        }

        if ($request->typeId && $request->typeId != 'all' ) {
            $tickets->where('tickets.type_id', '=', $request->typeId);
        }

        if ($request->tagId) {
            $tickets->join('ticket_tags', 'ticket_tags.ticket_id', 'tickets.id');
            $tickets->where('ticket_tags.tag_id', '=', $request->tagId);
        }

        return DataTables::of($tickets)
            ->addColumn('action', function ($row) {
                $action = '<div class="btn-group dropdown m-r-10">
                <button aria-expanded="false" data-toggle="dropdown" class="btn btn-default dropdown-toggle waves-effect waves-light" type="button"><i class="fa fa-gears "></i></button>
                <ul role="menu" class="dropdown-menu">';

                if ($this->user->can('view_tickets') || $this->user->can('edit_tickets')) {
                    $action .= '<li><a href="' . route("member.tickets.edit", $row->id) . '" ><i class="fa fa-eye"></i> ' . __('modules.client.viewDetails') . '</a></li>';
                }

                // if ($this->user->can('edit_tickets')) {
                //     $action .= '<li><a href="' . route("member.tickets.edit", $row->id) . '" ><i class="fa fa-pencil"></i> Edit</a></li>';
                // }

                if ($this->user->can('delete_tickets')) {
                    $action .= '<li><a href="javascript:;" class="sa-params" data-ticket-id="' . $row->id . '"><i class="fa fa-times"></i> Delete</a></li>';
                }
                $action .= '</ul></div>';
                return $action;
            })
            ->addColumn('others', function ($row) {
                $others = '<ul style="list-style: none; padding: 0; ">';

                if(!is_null($row->agent)){
                    $others .= '<li>' . __('modules.tickets.agent') . ': ' . (is_null($row->agent_id) ? "-" : ucwords($row->agent->name)) . '</li>';
                }
                    ;
                if ($row->status == 'open') {
                    $others .= '<li>' . __('app.status') . ': <label class="label label-danger">' . $row->status . '</label></li>';
                } elseif ($row->status == 'pending') {
                    $others .= '<li>' . __('app.status') . ': <label class="label label-warning">' . $row->status . '</label></li>';
                } elseif ($row->status == 'resolved') {
                    $others .= '<li>' . __('app.status') . ': <label class="label label-info">' . $row->status . '</label></li>';
                } elseif ($row->status == 'closed') {
                    $others .= '<li>' . __('app.status') . ': <label class="label label-success">' . $row->status . '</label></li>';
                }
                $others .= '<li>' . __('modules.tasks.priority') . ': ' . $row->priority . '</li>
                </ul>';
                return $others;
            })
            ->editColumn('subject', function ($row) {
                return ucfirst($row->subject);
            })
            ->editColumn('user_id', function ($row) {
                if(!is_null($row->requester)){
                    return ucwords($row->requester->name);
                }
                return '--';
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at->format($this->global->date_format);
            })
            ->rawColumns(['others', 'action'])
            ->removeColumn('agent_id')
            ->removeColumn('channel_id')
            ->removeColumn('type_id')
            ->removeColumn('updated_at')
            ->removeColumn('deleted_at')
            ->make(true);
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

    public function getGraphData($fromDate, $toDate, $agentId = null, $status = 'open', $priority = null, $channelId = null, $typeId = null)
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

    public function storeAdmin(StoreTicket $request)
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

        //        return Reply::redirect(route('member.tickets.index'), __('messages.ticketAddSuccess'));
    }

    public function updateAdmin(UpdateTicket $request, $id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->status = $request->status;
        $ticket->save();

        $lastMessage = null;

        if ($request->message != '') {
            //save first message
            $reply = new TicketReply();
            $reply->message = $request->message;
            $reply->ticket_id = $ticket->id;
            $reply->user_id = $this->user->id; //current logged in user
            $reply->save();

            $this->fileSave($request, $reply->id);

            $this->reply = $reply;
            $lastMessage = view('member.tickets.last-message', $this->data)->render();
        }

        return Reply::successWithData(__('messages.ticketReplySuccess'), ['lastMessage' => $lastMessage]);
    }

    public function updateAdminOtherData(UpdateTicket $request, $id)
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
        return Reply::success(__('messages.updateSuccess'));

    }

    /**
     * @param $id
     * @return array
     */
    public function destroyReply($id)
    {
        $ticketFiles = TicketFile::where('ticket_reply_id', $id)->get();

        foreach ($ticketFiles as $file) {
            Files::deleteFile($file->hashname, 'ticket-files/' . $file->ticket_reply_id);
            $file->delete();
        }
        TicketReply::destroy($id);
        return Reply::success(__('messages.deleteSuccess'));
    }

    /**
     * @param $request
     * @param $ticketReplyID
     */
    public function fileSave($request, $ticketReplyID)
    {
        if ($request->hasFile('file')) {
            foreach ($request->file as $fileData) {

                $file = new TicketFile();
                $file->user_id = $this->user->id;
                $file->ticket_reply_id = $ticketReplyID;
                $filename = Files::uploadLocalOrS3($fileData, 'ticket-files/'.$ticketReplyID);

                $file->filename = $fileData->getClientOriginalName();
                $file->hashname = $filename;
                $file->size = $fileData->getSize();
                $file->save();
            }
        }
    }
}
