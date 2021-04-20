<?php

namespace App\Http\Controllers\Client;

use App\Helper\Reply;
use App\Http\Controllers\Client\ClientBaseController;
use App\Notes;
use App\Http\Requests\VerifyPasswordRequest;
use App\User;
use http\Client;
use App\ClientUserNotes;
use Dotenv\Result\Success;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Hash;

class ClientNotesController extends ClientBaseController
{
    public function __construct() {
        parent::__construct();
        $this->pageTitle = 'app.menu.notes';
        $this->pageIcon = 'fa fa-sticky-note-o';
        
    }

    public function index() {
        return view('client.notes.index', $this->data);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function askForPassword($id)
    {
        $this->client = User::findClient($id);
        $this->notes = Notes::findOrFail($id);
        return view('client.notes.view', $this->data);
    }

    public function checkPassword(VerifyPasswordRequest $request)
    {
        $this->client = User::findClient($request->client_id);
        if(Hash::check($request->password,$this->client->password )) {
            $this->clients = User::allClients();
            $this->employees = User::allEmployees();
            $this->notes = Notes::findOrFail($request->note_id);
            $this->clientMembers = $this->notes->member->pluck('user_id')->toArray();
            $this->client_user_notes = ClientUserNotes::where('note_id','=',$this->notes->id)->get();
            $this->askPassword = 1;
            $view = view('client.notes.show', $this->data)->render();
            return Reply::successWithData('success', ['view' => $view]);
        } else {
            return Reply::error(__('messages.incorrectPassword'));
        }
    }


    public function show($id)
    {
        $this->clients = User::allClients();
        $this->employees = User::allEmployees();
        $this->notes = Notes::findOrFail($id);
        $this->clientMembers = $this->notes->member->pluck('user_id')->toArray();
        $this->client_user_notes = ClientUserNotes::where('note_id','=',$this->notes->id)->get();
        return view('client.notes.show', $this->data);
    }

    public function data()
    {
        $timeLogs = Notes::where('client_id', $this->user->id)->where('is_client_show', 1)
        ->orWhere('notes_type', 0);

        return DataTables::of($timeLogs)
            ->addColumn('action', function ($row) {
                $button ='';
                if($row->ask_password == 1){
                    $button .='<a href="javascript:;" class="btn btn-info btn-circle view-notes"
                    data-toggle="tooltip" data-contact-id="' . $row->id . '"  data-original-title="View">
                    <i class="fa fa-search" aria-hidden="true"></i></a>';
                }else{
                    $button .='<a href="javascript:;" class="btn btn-info btn-circle view-notes-modal"
                    data-toggle="tooltip" data-contact-id="' . $row->id . '"  data-original-title="View">
                    <i class="fa fa-search" aria-hidden="true"></i></a>';
                }
               
                 return $button;
            })
            ->editColumn('notes_title', function ($row) {
                
                return ucwords($row->notes_title);
            })
            ->editColumn('notes_type', function ($row) {
                if ( $row->notes_type == '0') {
                    return 'Public';
                } else{
                    return 'Private';
                }
                    
                
            })
            
            ->removeColumn('user_id')
            ->make(true);
    }
}
