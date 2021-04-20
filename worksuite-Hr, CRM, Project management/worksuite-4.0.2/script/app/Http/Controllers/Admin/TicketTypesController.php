<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Reply;
use App\Http\Requests\TicketType\StoreTicketType;
use App\Http\Requests\TicketType\UpdateTicketType;
use App\TicketType;

class TicketTypesController extends AdminBaseController
{
    public function __construct() {
        parent::__construct();
        $this->pageTitle = 'app.menu.ticketTypes';
        $this->pageIcon = 'ti-settings';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->ticketTypes = TicketType::all();
        return view('admin.ticket-settings.types.index', $this->data);
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
    public function store(StoreTicketType $request)
    {
        $type = new TicketType();
        $type->type = $request->type;
        $type->save();

        $allTypes = TicketType::all();

        $select = '';
        foreach($allTypes as $type){
            $select.= '<option value="'.$type->id.'">'.ucwords($type->type).'</option>';
        }

        return Reply::successWithData(__('messages.ticketTypeAddSuccess'), ['optionData' => $select]);
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
        $this->type = TicketType::findOrFail($id);
        return view('admin.ticket-settings.types.edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTicketType $request, $id)
    {
        $type = TicketType::findOrFail($id);
        $type->type = $request->type;
        $type->save();

        return Reply::success(__('messages.ticketTypeUpdateSuccess'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        TicketType::destroy($id);

        return Reply::success(__('messages.ticketTypeDeleteSuccess'));
    }

    public function createModal(){
        return view('admin.ticket-settings.types.create-modal');
    }
}
