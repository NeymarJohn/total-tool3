<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Reply;
use App\Http\Requests\CommonRequest;
use App\Http\Requests\Lead\StoreRequest;
use App\Http\Requests\Lead\UpdateRequest;
use App\Lead;
use App\LeadFollowUp;
use App\LeadSource;
use App\LeadStatus;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;

class FollowUpController extends AdminBaseController
{
    public function __construct() {
        parent::__construct();
        $this->pageIcon = 'user-follow';
        $this->pageTitle = 'app.menu.lead';
        $this->middleware(function ($request, $next) {
            if(!in_array('leads',$this->user->modules)){
                abort(403);
            }
            return $next($request);
        });

    }

    public function Index() {
        $this->totalLeads = Lead::count();
        return view('admin.lead.index', $this->data);
    }

    public function show($id) {
        $this->lead = Lead::findOrFail($id);
        return view('admin.lead.show', $this->data);
    }

    public function data($id = null) {
        $lead = Lead::select('leads.id','leads.client_id','leads.next_follow_up','client_name','company_name','lead_status.type as statusName','status_id','lead_sources.type as source')
                           ->leftJoin('lead_status', 'lead_status.id', 'leads.status_id')
                           ->leftJoin('lead_sources', 'lead_sources.id', 'leads.source_id')
            ->get();


        return DataTables::of($lead)
            ->addColumn('action', function($row){

                if($row->client_id == null || $row->client_id == ''){
                    $follow = '<li><a href="'.route('admin.clients.create').'/'.$row->id.'"><i class="fa fa-user"></i> Change To Client</a></li>';
                    if($row->next_follow_up == 'yes'){
                        $follow .= '<li onclick="followUp('.$row->id.')"><a href="javascript:;"><i class="fa fa-thumbs-up"></i> Add Follow Up</a></li>';
                    }
                }
                   else{
                       $follow = '';
                   }
                $action = '<div class="btn-group m-r-10">
                <button aria-expanded="false" data-toggle="dropdown" class="btn btn-info btn-outline  dropdown-toggle waves-effect waves-light" type="button">Action <span class="caret"></span></button>
                <ul role="menu" class="dropdown-menu">
                    <li><a href="'.route('admin.leads.show', $row->id).'"><i class="fa fa-search"></i> View</a></li>
                    <li><a href="'.route('admin.leads.edit', $row->id).'"><i class="fa fa-edit"></i> Edit</a></li>
                     '.$follow.'   
                </ul>
              </div>';
               return $action;
            })
            ->addColumn('status', function($row){
                $status = LeadStatus::all();
                $statusLi = '';
                foreach($status as $st) {
                    if($row->status_id === $st->id){
                        $selected = 'selected';
                    }else{
                        $selected = '';
                    }
                    $statusLi .= '<option '.$selected.' value="'.$st->id.'">'.$st->type.'</option>';
                }

                $action = '<select class="form-control" name="statusChange" onchange="changeStatus( '.$row->id.', this.value)">
                    '.$statusLi.'
                </select>';

                if($row->client_id != null && $row->client_id != ''){
                    $label = '<label class="label label-success">'.__('app.client').'</label>';
                }
                else{
                    $label = '<label class="label label-info">'.__('app.lead').'</label>';
                }

                return $action .' ' . $label;
            })
            ->removeColumn('status_id')
            ->removeColumn('client_id')
            ->removeColumn('source')
            ->removeColumn('next_follow_up')
            ->removeColumn('statusName')
            ->rawColumns(['status','action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->sources = LeadSource::all();
        $this->status = LeadStatus::all();
        return view('admin.lead.create', $this->data);
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
        $lead->client_name = $request->client_name;
        $lead->client_email = $request->client_email;
        $lead->mobile = $request->mobile;
        $lead->note = $request->note;
        $lead->next_follow_up = $request->next_follow_up;
        $lead->save();

        return Reply::redirect(route('admin.leads.index'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->lead = Lead::findOrFail($id);
        $this->sources = LeadSource::all();
        $this->status = LeadStatus::all();
        return view('admin.lead.edit', $this->data);
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
        $lead->save();

        return Reply::redirect(route('admin.leads.index'));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Lead::destroy($id);
        return Reply::success(__('messages.LeadDeleted'));
    }

    /**
     * @param CommonRequest $request
     * @return array
     */
    public function changeStatus(CommonRequest $request)
    {
        $lead = Lead::findOrFail($request->leadID);
        $lead->status_id = $request->statusID;
        $lead->save();

        return Reply::success(__('messages.leadStatusChangeSuccess'));
    }

    /**
     * @param $leadID
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function followUpCreate($leadID){
        $this->leadID = $leadID;
        return view('admin.lead.follow_up', $this->data);
    }

    /**
     * @param CommonRequest $request
     * @return array
     */
    public function followUpStore(CommonRequest $request){

        $followUp = new LeadFollowUp();
        $followUp->lead_id = $request->lead_id;
        $followUp->next_follow_up_date = Carbon::createFromFormat($this->global->date_format, $request->follow_up_next)->format('Y-m-d');
        $followUp->remark = $request->remark;
        $followUp->save();

        return Reply::success(__('messages.leadFollowUpAddedSuccess'));
    }

    public function followUpShow($leadID){
        $this->leadID = $leadID;
        $this->lead = Lead::findOrFail($leadID);
        $this->follows = Lead::findOrFail($leadID);
        return view('admin.lead.followup.show', $this->data);
    }

    public function editFollow($id)
    {
        $this->follow = LeadFollowUp::findOrFail($id);
        $view = view('admin.lead.followup.edit', $this->data)->render();
        return Reply::dataOnly(['html' => $view]);
    }
}
