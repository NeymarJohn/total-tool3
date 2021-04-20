<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Reply;
use App\Http\Requests\LeadSetting\StoreLeadStatus;
use App\Http\Requests\LeadSetting\UpdateLeadStatus;
use App\Lead;
use App\LeadStatus;

class LeadStatusSettingController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.leadStatus';
        $this->pageIcon = 'ti-settings';
        $this->middleware(function ($request, $next) {
            if (!in_array('leads', $this->modules)) {
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
        $this->leadStatus = LeadStatus::all();
        return view('admin.lead-settings.status.index', $this->data);
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
    public function store(StoreLeadStatus $request)
    {
        $maxPriority = LeadStatus::max('priority');

        $status = new LeadStatus();
        $status->type = $request->type;
        $status->label_color = $request->label_color;
        $status->priority = ($maxPriority + 1);
        $status->save();

        $allStatus = LeadStatus::all();

        $select = '';
        foreach ($allStatus as $sts) {
            $select .= '<option value="' . $sts->id . '">' . ucwords($sts->type) . '</option>';
        }

        return Reply::successWithData(__('messages.leadStatusAddSuccess'), ['optionData' => $select]);
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
        $this->status = LeadStatus::findOrFail($id);
        $this->maxPriority = LeadStatus::max('priority');

        return view('admin.lead-settings.status.edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateLeadStatus $request, $id)
    {
        $type = LeadStatus::findOrFail($id);
        $oldPosition = $type->priority;
        $newPosition = $request->priority;

        if ($oldPosition < $newPosition) {

            $otherColumns = LeadStatus::where('priority', '>', $oldPosition)
                ->where('priority', '<=', $newPosition)
                ->orderBy('priority', 'asc')
                ->get();

            foreach ($otherColumns as $column) {
                $pos = LeadStatus::where('priority', $column->priority)->first();
                $pos->priority = ($pos->priority - 1);
                $pos->save();
            }
        } else if ($oldPosition > $newPosition) {

            $otherColumns = LeadStatus::where('priority', '<', $oldPosition)
                ->where('priority', '>=', $newPosition)
                ->orderBy('priority', 'asc')
                ->get();

            foreach ($otherColumns as $column) {
                $pos = LeadStatus::where('priority', $column->priority)->first();
                $pos->priority = ($pos->priority + 1);
                $pos->save();
            }
        }

        $type->type = $request->type;
        $type->label_color = $request->label_color;
        $type->priority = $request->priority;
        $type->save();

        return Reply::success(__('messages.leadStatusUpdateSuccess'));
    }

    public function statusUpdate($id){
        $allLeadStatus = LeadStatus::select('id', 'default')->get();
        foreach($allLeadStatus as $leadStatus){
            if($leadStatus->id == $id){
                $leadStatus->default = '1';
            }
            else{
                $leadStatus->default = '0';
            }
            $leadStatus->save();
        }
        return Reply::success(__('messages.leadStatusUpdateSuccess'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $defaultLeadStatus = LeadStatus::where('default', 1)->first();
        Lead::where('status_id', $id)->update(['status_id' => $defaultLeadStatus->id]);

        $board = LeadStatus::findOrFail($id);

        $otherColumns = LeadStatus::where('priority', '>', $board->priority)
            ->orderBy('priority', 'asc')
            ->get();

        foreach ($otherColumns as $column) {
            $pos = LeadStatus::where('priority', $column->priority)->first();
            $pos->priority = ($pos->priority - 1);
            $pos->save();
        }
        
        LeadStatus::destroy($id);

        return Reply::success(__('messages.leadStatusDeleteSuccess'));
    }

    public function createModal()
    {
        return view('admin.lead-settings.status.create-modal');
    }
}
