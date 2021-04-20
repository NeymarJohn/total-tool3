<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Reply;
use App\Http\Requests\LeadSetting\StoreLeadSource;
use App\Http\Requests\LeadSetting\UpdateLeadSource;
use App\LeadSource;

class LeadSourceSettingController extends AdminBaseController
{
    public function __construct() {
        parent::__construct();
        $this->pageTitle = 'app.menu.leadSource';
        $this->pageIcon = 'ti-settings';
        $this->middleware(function ($request, $next) {
            if(!in_array('leads',$this->user->modules)){
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
        $this->leadSources = LeadSource::all();
        return view('admin.lead-settings.source.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.lead-settings.source.create-modal', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreLeadSource $request)
    {
        $source = new LeadSource();
        $source->type = $request->type;
        $source->save();

        $allSources = LeadSource::all();

        $select = '';
        foreach($allSources as $allSource){
            $select.= '<option value="'.$allSource->id.'">'.ucwords($allSource->type).'</option>';
        }

        return Reply::successWithData(__('messages.leadSourceAddSuccess'), ['optionData' => $select]);
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
        $this->source = LeadSource::findOrFail($id);

        return view('admin.lead-settings.source.edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateLeadSource $request, $id)
    {
        $type = LeadSource::findOrFail($id);
        $type->type = $request->type;
        $type->save();

        return Reply::success(__('messages.leadSourceUpdateSuccess'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        LeadSource::destroy($id);

        return Reply::success(__('messages.leadSourceDeleteSuccess'));
    }

    public function createModal(){
        return view('admin.lead-settings.source.create-modal');
    }
}
