<?php

namespace App\Http\Controllers\Member;

use App\Helper\Reply;
use App\Http\Requests\CommonRequest;
use App\Http\Requests\FollowUp\UpdateFollowUpRequest;
use App\Http\Requests\Lead\StoreRequest;
use App\Http\Requests\Lead\UpdateRequest;
use App\Lead;
use App\LeadAgent;
use App\LeadFollowUp;
use App\LeadSource;
use App\LeadStatus;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;

class MemberLeadController extends MemberBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageIcon = __('icon-people');
        $this->pageTitle = 'app.menu.lead';
        $this->middleware(function ($request, $next) {
            abort_if(!in_array('leads', $this->user->modules),403);
            return $next($request);
        });
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {

        $agent = LeadAgent::where('user_id', $this->user->id)->first();
        $agentId = ($agent) ? $agent->id : '';


        if (!$this->user->cans('view_lead')) {
            $this->totalLeads = Lead::where('leads.agent_id', $agentId)->get();
        } else {
            $this->totalLeads = Lead::all();
        }

        $this->totalClientConverted = $this->totalLeads->filter(function ($value, $key) {
            return $value->client_id != null;
        });
        $this->totalLeads = $this->totalLeads->count();
        $this->totalClientConverted = $this->totalClientConverted->count();

        $pendingLeadFollowUps = LeadFollowUp::where(\DB::raw('DATE(next_follow_up_date)'), '<=', Carbon::today()->format('Y-m-d'))
            ->join('leads', 'leads.id', 'lead_follow_up.lead_id')
            ->where('leads.next_follow_up', 'yes');

        if (!$this->user->cans('view_lead')) {
            $pendingLeadFollowUps = $pendingLeadFollowUps->where('leads.agent_id', $this->user->id);
        }

        $this->pendingLeadFollowUps = $pendingLeadFollowUps->count();
        $this->leadAgents = LeadAgent::with('user')->has('user')->get();

        return view('member.lead.index', $this->data);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $this->lead = Lead::with(['lead_agent'])->findOrFail($id);
        if (!$this->user->cans('view_lead') && $this->lead->lead_agent->user_id != $this->user->id) {
            abort(403);
        }
        return view('member.lead.show', $this->data);
    }

    /**
     * @param CommonRequest $request
     * @param null $id
     * @return mixed
     */
    public function data(CommonRequest $request, $id = null)
    {
        $currentDate = Carbon::today()->format('Y-m-d');
        $lead = Lead::select(
            'leads.id',
            'leads.client_id',
            'leads.next_follow_up',
            'client_name',
            'company_name',
            'lead_status.type as statusName',
            'status_id',
            'leads.created_at',
            'lead_sources.type as source',
            'lead_agents.user_id as agent_user_id',
            \DB::raw("(select next_follow_up_date from lead_follow_up where lead_id = leads.id and leads.next_follow_up  = 'yes' and DATE(next_follow_up_date) >= {$currentDate} ORDER BY next_follow_up_date asc limit 1) as next_follow_up_date")
        )
            ->leftJoin('lead_status', 'lead_status.id', 'leads.status_id')
            ->leftJoin('lead_sources', 'lead_sources.id', 'leads.source_id')
            ->leftJoin('lead_agents', 'lead_agents.id', 'leads.agent_id');

        if ($request->followUp != 'all' && $request->followUp != '' && $request->followUp != 'undefined') {
            $lead = $lead->leftJoin('lead_follow_up', 'lead_follow_up.lead_id', 'leads.id')
                ->where('leads.next_follow_up', 'yes')
                ->where('lead_follow_up.next_follow_up_date', '<', $currentDate);
        }

        if ($request->client != 'all' && $request->client != ''  && $request->client != 'undefined') {
            if ($request->client == 'lead') {
                $lead = $lead->whereNull('client_id');
            } else {
                $lead = $lead->whereNotNull('client_id');
            }
        }

        if ($request->agent != 'all' && $request->agent != '' && $request->has('agent') && $request->agent != 'undefined') {
            $lead = $lead->where('agent_id', $request->agent);
        }

        if (!$this->user->cans('view_lead')) {
            $agent = LeadAgent::where('user_id', $this->user->id)->first();
            $agentId = ($agent) ? $agent->id : '';
            $lead = $lead->where('leads.agent_id', $agentId);
        }

        $lead = $lead->groupBy('leads.id')->get();

        return DataTables::of($lead)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $follow = '';
                if (($row->client_id == null || $row->client_id == '' || $row->agent_user_id == $this->user->id)) {
                    if ($this->user->cans('add_clients')) {
                        $follow = '<li><a href="' . route('member.clients.create') . '/' . $row->id . '"><i class="fa fa-user"></i> ' . __('modules.lead.changeToClient') . '</a></li>';
                    }
                    if ($row->next_follow_up == 'yes' && ($this->user->cans('edit_lead') || $row->agent_user_id == $this->user->id)) {
                        $follow .= '<li onclick="followUp(' . $row->id . ')"><a href="javascript:;"><i class="fa fa-thumbs-up"></i> ' . __('modules.lead.addFollowUp') . '</a></li>';
                    }
                }

                if ($this->user->cans('edit_lead') && ($row->client_id == null || $row->client_id == '')) {
                    $edit = '<li><a href="' . route('member.leads.edit', $row->id) . '"><i class="fa fa-edit"></i> ' . __('modules.lead.edit') . '</a></li>';
                } else {
                    $edit = '';
                }
                if ($this->user->cans('delete_lead')) {
                    $delete = '<li><a href="javascript:;" class="sa-params" data-user-id="' . $row->id . '"><i class="fa fa-trash "></i> ' . __('app.delete') . '</a></li>';
                } else {
                    $delete = '';
                }
                $action = '<div class="btn-group m-r-10">
                <button aria-expanded="false" data-toggle="dropdown" class="btn btn-info btn-outline  dropdown-toggle waves-effect waves-light" type="button">' . __('modules.lead.action') . ' <span class="caret"></span></button>
                <ul role="menu" class="dropdown-menu">
                    <li><a href="' . route('member.leads.show', $row->id) . '"><i class="fa fa-search"></i> ' . __('modules.lead.view') . '</a></li>
                     ' . $edit . '   
                     ' . $follow . '   
                     ' . $delete . '   
                </ul>
              </div>';
                return $action;
            })
            ->addColumn('status', function ($row) {
                $status = LeadStatus::all();
                $statusLi = '';
                $statusName = '';
                foreach ($status as $st) {
                    if ($row->status_id == $st->id) {
                        $selected = 'selected';
                    } else {
                        $selected = '';
                    }
                    $statusLi .= '<option ' . $selected . ' value="' . $st->id . '">' . $st->type . '</option>';
                    $statusName =  $st->type;
                }

                $action = '<select class="form-control statusChange" name="statusChange" onchange="changeStatus( ' . $row->id . ', this.value)">
                    ' . $statusLi . '
                </select>';

                // if (!$this->user->cans('view_lead')) {
                //     return ucwords($statusName);
                // }
                return $action;
            })
            ->editColumn('client_name', function ($row) {
                if ($row->client_id != null && $row->client_id != '') {
                    $label = '<label class="label label-success">' . __('app.client') . '</label>';
                } else {
                    $label = '<label class="label label-info">' . __('app.lead') . '</label>';
                }

                return $row->client_name . '<div class="clearfix"></div> ' . $label;
            })
            ->editColumn('next_follow_up_date', function ($row) use ($currentDate) {
                if ($row->next_follow_up_date != null && $row->next_follow_up_date != '') {
                    $date = Carbon::parse($row->next_follow_up_date)->format($this->global->date_format .' '.$this->global->time_format);
                } else {
                    $date = '--';
                }
                if ($row->next_follow_up_date < $currentDate && $date != '--') {
                    return $date . ' <label class="label label-danger">' . __('app.pending') . '</label>';
                }

                return $date;
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at->format($this->global->date_format);
            })
            ->removeColumn('status_id')
            ->removeColumn('client_id')
            ->removeColumn('source')
            ->removeColumn('next_follow_up')
            ->removeColumn('statusName')
            ->rawColumns(['status', 'action', 'client_name', 'next_follow_up_date'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       abort_if(!$this->user->cans('add_lead'),403);
        $this->leadAgents = LeadAgent::with('user')->get();
        $this->sources = LeadSource::all();
        $this->status = LeadStatus::all();
        return view('member.lead.create', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $lead = new Lead();
        $lead->company_name = $request->company_name;
        $lead->website = $request->website;
        $lead->address = $request->address;
        $lead->client_name = $request->name;
        $lead->client_email = $request->email;
        $lead->mobile = $request->mobile;
        $lead->note = $request->note;
        $lead->next_follow_up = $request->next_follow_up;
        $lead->agent_id = $request->agent_id;
        $lead->source_id = $request->source_id;

        $lead->save();

        return Reply::redirect(route('member.leads.index'), __('messages.LeadAddedUpdated'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       abort_if(!$this->user->cans('edit_lead'),403);
        $this->leadAgents = LeadAgent::with('user')->get();
        $this->lead = Lead::findOrFail($id);
        $this->sources = LeadSource::all();
        $this->status = LeadStatus::all();
        return view('member.lead.edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $id)
    {
       abort_if(!$this->user->cans('edit_lead'),403);
        $lead = Lead::findOrFail($id);
        $lead->company_name = $request->company_name;
        $lead->website = $request->website;
        $lead->address = $request->address;
        $lead->client_name = $request->client_name;
        $lead->client_email = $request->client_email;
        $lead->mobile = $request->mobile;
        $lead->note = $request->note;
        $lead->status_id = $request->status;
        $lead->source_id = $request->source;
        $lead->next_follow_up = $request->next_follow_up;
        $lead->agent_id = $request->agent_id;

        $lead->save();

        return Reply::redirect(route('member.leads.index'), __('messages.LeadUpdated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       abort_if(!$this->user->cans('delete_lead'),403);
        Lead::destroy($id);
        return Reply::success(__('messages.LeadDeleted'));
    }

    /**
     * @param $id
     * @return array
     */
    public function deleteFollow($id)
    {
        LeadFollowUp::destroy($id);
        return Reply::success(__('messages.followUp.deletedSuccess'));
    }

    /**
     * @param CommonRequest $request
     * @return array
     */
    public function changeStatus(CommonRequest $request)
    {
        // if (!$this->user->cans('edit_lead')) {
        //     abort(403);
        // }
        $lead = Lead::findOrFail($request->leadID);
        $lead->status_id = $request->statusID;
        $lead->save();

        return Reply::success(__('messages.leadStatusChangeSuccess'));
    }

    /**
     * @param $leadID
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function followUpCreate($leadID)
    {
        $lead = Lead::with('lead_agent')->findOrFail($leadID);
        if ($this->user->cans('edit_lead') || $lead->lead_agent->user_id == $this->user->id) {
            $this->leadID = $leadID;
            return view('member.lead.follow_up', $this->data);
        }

        abort(403);
    }

    /**
     * @param CommonRequest $request
     * @return array
     */
    public function followUpStore(\App\Http\Requests\FollowUp\StoreRequest $request)
    {
        $this->lead = Lead::findOrFail($request->lead_id);
        if ($this->user->cans('edit_lead') || $this->lead->lead_agent->user_id == $this->user->id) {
            $followUp = new LeadFollowUp();
            $followUp->lead_id = $request->lead_id;
            if($request->has('type')){
                $followUp->next_follow_up_date =  Carbon::createFromFormat('d/m/Y H:i', $request->next_follow_up_date)->format('Y-m-d H:i:s');
            }
            else{
                $followUp->next_follow_up_date = Carbon::createFromFormat($this->global->date_format, $request->next_follow_up_date)->format('Y-m-d');
            }
            $followUp->remark = $request->remark;
            $followUp->save();


            $view = view('member.lead.followup.task-list-ajax', $this->data)->render();

            return Reply::successWithData(__('messages.leadFollowUpAddedSuccess'), ['html' => $view]);
        }

        abort(403);
    }

    /**
     * @param $leadID
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function followUpShow($leadID)
    {
       abort_if(!$this->user->cans('edit_lead'),403);

        $this->leadID = $leadID;
        $this->lead = Lead::findOrFail($leadID);
        return view('member.lead.followup.show', $this->data);
    }

    public function editFollow($id)
    {
       abort_if(!$this->user->cans('edit_lead'),403);

        $this->follow = LeadFollowUp::findOrFail($id);
        $view = view('member.lead.followup.edit', $this->data)->render();
        return Reply::dataOnly(['html' => $view]);
    }

    /**
     * @param \App\Http\Requests\FollowUp\StoreRequest $request
     * @return array
     * @throws \Throwable
     */
    public function UpdateFollow(UpdateFollowUpRequest $request)
    {
       abort_if(!$this->user->cans('edit_lead'),403);

        $followUp = LeadFollowUp::findOrFail($request->id);
        $followUp->lead_id = $request->lead_id;
        if($request->has('type')){
            $followUp->next_follow_up_date =  Carbon::createFromFormat('d/m/Y H:i', $request->next_follow_up_date)->format('Y-m-d H:i:s');
        }
        else{
            $followUp->next_follow_up_date = Carbon::createFromFormat($this->global->date_format, $request->next_follow_up_date)->format('Y-m-d');
        }        $followUp->remark = $request->remark;
        $followUp->save();

        $this->lead = Lead::findOrFail($request->lead_id);

        $view = view('member.lead.followup.task-list-ajax', $this->data)->render();

        return Reply::successWithData(__('messages.leadFollowUpUpdatedSuccess'), ['html' => $view]);
    }

    /**
     * @param CommonRequest $request
     * @return array
     * @throws \Throwable
     */
    public function followUpSort(CommonRequest $request)
    {
       abort_if(!$this->user->cans('edit_lead'),403);

        $leadId = $request->leadId;
        $this->sortBy = $request->sortBy;

        $this->lead = Lead::findOrFail($leadId);
        if ($request->sortBy == 'next_follow_up_date') {
            $order = "asc";
        } else {
            $order = "desc";
        }

        $follow = LeadFollowUp::where('lead_id', $leadId)->orderBy($request->sortBy, $order);


        $this->lead->follow = $follow->get();

        $view = view('member.lead.followup.task-list-ajax', $this->data)->render();

        return Reply::successWithData(__('messages.followUpFilter'), ['html' => $view]);
    }
}
