<?php

namespace App\Http\Controllers\Member;

use App\Helper\Reply;
use App\Notes;
use App\User;
use http\Client;
use App\ClientUserNotes;
use App\Http\Requests\ProjectNotes\UpdateNotes;
use App\ClientDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\VerifyPasswordRequest;
use Illuminate\Support\Facades\Hash;

class MemberNotesController extends MemberBaseController
{
    public function __construct() {
        parent::__construct();
        $this->pageTitle = 'app.menu.notes';
        $this->pageIcon = 'fa fa-sticky-note-o';
        
    }

   
    public function show($id) {
        $this->client = User::findClient($id);
        return view('member.notes.index', $this->data);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    
    public function edit($id)
    {
        $this->clients = User::allClients();
        $this->employees = User::allEmployees()->where('id','!=',$this->user->id);
        $this->notes = Notes::findOrFail($id);
      
        $this->client_user_notes = ClientUserNotes::where('note_id','=',$this->notes->id)->get();
        $this->clientMembers = $this->notes->member->pluck('user_id')->toArray();
        return view('member.notes.edit', $this->data);

    }
    public function update(UpdateNotes $request, $id)
    {
        $note = Notes::findOrFail($id);
        $note->notes_title = $request->notes_title;
        $note->notes_type = $request->notes_type;
        $note->is_client_show = $request->is_client_show == "on" ? 1 : 0;
        $note->ask_password = $request->ask_password == "on" ? 1 : 0;
        $note->note_details = $request->note_details;
        $note->save();
        ClientUserNotes::where('note_id', $note->id)->delete();
        if($request->notes_type == 1){
            $users = $request->user_id;
            if(!is_null($users)){
                foreach ($users as $user) {
                    $member = ClientUserNotes::firstOrCreate([
                        'user_id' => $user,
                        'note_id' => $note->id
                    ]);
                }
            }
        }
        return Reply::success(__('messages.notesUpdated'));
    }
    public function view($id)
    {
        $this->clients = User::allClients();
        $this->employees = User::allEmployees();
        $this->notes = Notes::findOrFail($id);
        $this->clientMembers = $this->notes->member->pluck('user_id')->toArray();
        $this->client_user_notes = ClientUserNotes::where('note_id','=',$this->notes->id)->get();
        return view('member.notes.view', $this->data);
    }
    public function askForPassword($id)
    {
        $this->client = User::findClient($id);
        $this->notes = Notes::findOrFail($id);
        return view('member.notes.verify-password', $this->data);
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
            $view = view('member.notes.view', $this->data)->render();
            return Reply::successWithData('success', ['view' => $view]);
        } else {
            return Reply::error(__('messages.incorrectPassword'));
        }
    } 
    public function data()
    {
        $this->userDetail = auth()->user();
        $timeLogs = Notes::select('notes.notes_title','notes.notes_type','notes.id','notes.ask_password',
            'notes.note_details')->leftjoin('client_user_notes', 'client_user_notes.note_id', '=', 'notes.id');
          
        if (!$this->user->cans('view_projects')) {
            $timeLogs = $timeLogs
            ->where('client_user_notes.user_id', '=', $this->userDetail->id)
            ->orwhere('notes.notes_type',0);
        }else{
            $timeLogs = $timeLogs;
            
        }
        $timeLogs->groupBy('notes.id')->get();
        return DataTables::of($timeLogs)
            ->addColumn('action', function ($row) {
                $button ='';
                if($this->user->cans('edit_projects')){
                     $button .= '<a href="javascript:;" class="btn btn-success btn-circle edit-contact"
                        data-toggle="tooltip" data-contact-id="' . $row->id . '"  data-original-title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
                }
                if($row->ask_password != 1){
                    $button .=' <a href="javascript:;" class="btn btn-info btn-circle view-notes"
                    data-toggle="tooltip" data-contact-id="' . $row->id . '"  data-original-title="View"><i class="fa fa-search" aria-hidden="true"></i></a>';
                }else{
                    $button .=' <a href="javascript:;" class="btn btn-info btn-circle view-notes-modal"
                    data-toggle="tooltip" data-contact-id="' . $row->id . '"  data-original-title="View"><i class="fa fa-search" aria-hidden="true"></i></a>';
                }
                    if ($this->user->cans('delete_projects')) {  
                    $button .=  ' <a href="javascript:;" class="btn btn-danger btn-circle sa-params"
                            data-toggle="tooltip" data-contact-id="' . $row->id . '" data-original-title="Delete"><i class="fa fa-times" aria-hidden="true"></i></a>';
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
   
    public function destroy($id)
    {
        
        Notes::destroy($id);

        return Reply::success(__('messages.notesDeleted'));
    }
}
