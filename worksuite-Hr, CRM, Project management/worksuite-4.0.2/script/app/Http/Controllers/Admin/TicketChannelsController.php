<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Reply;
use App\Http\Requests\TicketChannel\StoreTicketChannel;
use App\Http\Requests\TicketChannel\UpdateTicketChannel;
use App\TicketChannel;

class TicketChannelsController extends AdminBaseController
{

    public function __construct() {
        parent::__construct();
        $this->pageTitle = 'app.menu.ticketChannel';
        $this->pageIcon = 'ti-settings';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->channels = TicketChannel::all();
        return view('admin.ticket-settings.channels.index', $this->data);
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
    public function store(StoreTicketChannel $request)
    {
        $channel = new TicketChannel();
        $channel->channel_name = $request->channel_name;
        $channel->save();

        $allChannels = TicketChannel::all();

        $select = '';
        foreach($allChannels as $channel){
            $select.= '<option value="'.$channel->id.'">'.ucwords($channel->channel_name).'</option>';
        }

        return Reply::successWithData(__('messages.ticketChannelAddSuccess'), ['optionData' => $select]);
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
        $this->channel = TicketChannel::findOrFail($id);
        return view('admin.ticket-settings.channels.edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTicketChannel $request, $id)
    {
        $channel = TicketChannel::findOrFail($id);
        $channel->channel_name = $request->channel_name;
        $channel->save();

        return Reply::success(__('messages.ticketChannelUpdateSuccess'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        TicketChannel::destroy($id);

        return Reply::success(__('messages.ticketChannelDeleteSuccess'));
    }


    public function createModal(){
        return view('admin.ticket-settings.channels.create-modal');
    }

}
