<?php

namespace App\Http\Controllers\Member;

use App\Helper\Reply;
use App\Http\Requests\Tickets\UpdateTicket;
use App\Ticket;
use App\TicketAgentGroups;
use App\TicketChannel;
use App\TicketFile;
use App\TicketReply;
use App\TicketReplyTemplate;
use App\TicketTag;
use App\TicketTagList;
use App\TicketType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use App\Helper\Files;

class MemberTicketsAgentController extends MemberBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.agentDashboard';
        $this->pageIcon = 'ti-ticket';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $isAgent = TicketAgentGroups::where('agent_id', $this->user->id)->first();
        if (!$isAgent) {
            return redirect(route('member.tickets.index'));
        }

        $this->startDate = Carbon::now()->subWeek(1)->timezone($this->global->timezone)->format('m/d/Y');
        $this->endDate = Carbon::now()->timezone($this->global->timezone)->format('m/d/Y');
        $this->channels = TicketChannel::all();
        $this->types = TicketType::all();
        return view('member.tickets.agent.index', $this->data);
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
    public function store(Request $request)
    {
        //
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
        $isAgent = TicketAgentGroups::where('agent_id', $this->user->id)->first();
        if (!$isAgent) {
            return redirect(route('member.tickets.index'));
        }

        $this->ticket = Ticket::findOrFail($id);
        $this->types = TicketType::all();
        $this->channels = TicketChannel::all();
        $this->templates = TicketReplyTemplate::all();
        return view('member.tickets.agent.edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTicket $request, $id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->status = $request->status;
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

            $this->fileSave($request, $reply->id);

            // save tags
            // $tags = $request->tags;
            // if ($tags) {
            //     TicketTag::where('ticket_id', $ticket->id)->delete();

            //     foreach ($tags as $tag) {
            //         $tag = TicketTagList::firstOrCreate([
            //             'tag_name' => $tag
            //         ]);


            //         TicketTag::create([
            //             'tag_id' => $tag->id,
            //             'ticket_id' => $ticket->id
            //         ]);
            //     }
            // }
            $this->reply = $reply;
            $lastMessage = view('member.tickets.agent.last-message', $this->data)->render();
        }

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
        //
    }

    public function getGraphData($fromDate, $toDate,  $priority = null,$status = 'open', $channelId = null, $typeId = null)
    {
        $graphData  = [];
        $resolved   = [];
        $pending    = [];
        $open       = [];
        $closed     = [];

        $totalTickets = Ticket::with('reply')
            ->selectRaw('DATE_FORMAT(created_at,"%Y-%m-%d") as date, count(id) as total, status')
            ->groupBy('created_at')
            ->where('agent_id', $this->user->id)
            ->orderBy('created_at', 'ASC');

        if ($fromDate) {
            $startDate = Carbon::createFromFormat($this->global->date_format, $fromDate)->toDateString();
            $totalTickets->where(DB::raw('DATE(`created_at`)'), '>=', $startDate);
        }
        if ($toDate) {
            $endDate = Carbon::createFromFormat($this->global->date_format, $toDate)->toDateString();
            $totalTickets->where(DB::raw('DATE(`created_at`)'), '<=', $endDate);
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

    public function data(Request $request)
    {
        $tickets = Ticket::select('*')
            ->where('agent_id', $this->user->id);

        if ($request->startDate && $request->startDate != '') {
            $startDate = Carbon::createFromFormat($this->global->date_format, $request->startDate)->toDateString();
            $tickets->where(DB::raw('DATE(tickets.created_at)'), '>=', $startDate);
        }

        if ($request->endDate && $request->endDate != '') {
            $endDate = Carbon::createFromFormat($this->global->date_format, $request->endDate)->toDateString();
            $tickets->where(DB::raw('DATE(tickets.created_at)'), '<=', $endDate);
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

        return DataTables::of($tickets)
            ->addColumn('action', function ($row) {
                return '<a class="btn btn-info btn-outline btn-sm" href="' . route("member.ticket-agent.edit", $row->id) . '" ><i class="fa fa-eye"></i> ' . __('modules.client.viewDetails') . '</a>';
            })
            ->addColumn('others', function ($row) {
                $others = '<ul style="list-style: none; padding: 0; font-size: small; line-height: 1.8em;">
                    <li><span>' . __('modules.tickets.agent') . '</span>: ' . (is_null($row->agent_id) ? "-" : ucwords($row->agent->name)) . '</li>';
                if ($row->status == 'open') {
                    $others .= '<li><span>' . __('app.status') . '</span>: <label class="label label-danger">' . $row->status . '</label></li>';
                } elseif ($row->status == 'pending') {
                    $others .= '<li><span>' . __('app.status') . '</span>: <label class="label label-warning">' . $row->status . '</label></li>';
                } elseif ($row->status == 'resolved') {
                    $others .= '<li><span>' . __('app.status') . '</span>: <label class="label label-info">' . $row->status . '</label></li>';
                } elseif ($row->status == 'closed') {
                    $others .= '<li><span>' . __('app.status') . '</span>: <label class="label label-success">' . $row->status . '</label></li>';
                }
                $others .= '<li><span>' . __('modules.tasks.priority') . '</span>: ' . $row->priority . '</li>
                </ul>';
                return $others;
            })
            ->editColumn('subject', function ($row) {
                return ucfirst($row->subject);
            })
            ->editColumn('user_id', function ($row) {
                return ucwords($row->requester->name);
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at->format($this->global->date_format . ' ' . $this->global->time_format);
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
        $tickets = Ticket::with('agent')->where('agent_id', $this->user->id);;

        if ($request->startDate) {
            $startDate = Carbon::createFromFormat($this->global->date_format, $request->startDate)->toDateString();
            $tickets->where(DB::raw('DATE(`created_at`)'), '>=', $startDate);
        }
        if ($request->endDate) {
            $endDate = Carbon::createFromFormat($this->global->date_format, $request->endDate)->toDateString();
            $tickets->where(DB::raw('DATE(`created_at`)'), '<=', $endDate);
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

        $chartData = $this->getGraphData($request->startDate, $request->endDate, $request->priority, $request->status, $request->channelId, $request->typeId);

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


    public function fetchTemplate(Request $request)
    {
        $templateId = $request->templateId;
        $template = TicketReplyTemplate::findOrFail($templateId);
        return Reply::dataOnly(['replyText' => $template->reply_text, 'status' => 'success']);
    }
  
    public function updateAgentOtherData(UpdateTicket $request, $id)
    {
        $ticket = Ticket::findOrFail($id);
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
