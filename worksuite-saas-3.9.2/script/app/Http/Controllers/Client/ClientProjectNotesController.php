<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\ProjectNotes\StoreNotes;
use App\Project;
use App\ProjectNotes;
use App\ProjectUserNotes;
use App\User;
use App\Helper\Reply;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\VerifyPasswordRequest;


class ClientProjectNotesController extends ClientBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.projects';
        $this->pageIcon = 'icon-layers';
        $this->middleware(function ($request, $next) {
            abort_if(!in_array('projects', $this->user->modules),403);
            return $next($request);
        });
    }
    
   
   
    public function data($id)
    {
        $timeLogs = ProjectNotes::where('client_id', $this->user->id)
        ->where('is_client_show', 1)->orwhere('notes_type',0);
        
        return DataTables::of($timeLogs)
            ->addColumn('action', function ($row) {
                $button ='';
                if($row->ask_password == 1){
                return '<a href="javascript:;" class="btn btn-success btn-circle view-contact"
                      data-toggle="tooltip" data-contact-id="' . $row->id . '"  data-original-title="View"><i class="fa fa-search" aria-hidden="true"></i></a>';
                    }else{
                        $button .='<a href="javascript:;" class="btn btn-success btn-circle view-notes-modal"
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
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->clients = User::allClients();
        $this->employees = User::allEmployees()->where('id','!=',$this->user->id);
        $this->project = Project::findOrFail($id);
        
        return view('client.projects.notes.show', $this->data);
    }

    
    
    public function view($id)
    {
         $this->clients = User::allClients();
        $this->employees = User::allEmployees();
        $this->notes = ProjectNotes::findOrFail($id);
        $this->noteMembers = $this->notes->member->pluck('user_id')->toArray();
        return view('client.projects.notes.view', $this->data);
    }
   
    public function askForPassword($id)
    {
        $this->client = User::findClient($id);
        $this->notes = ProjectNotes::findOrFail($id);
        return view('client.projects.notes.verify_password', $this->data);
    }
    public function checkPassword(VerifyPasswordRequest $request)
    {
        $this->client = User::findClient($request->client_id);
        if(Hash::check($request->password,$this->client->password )) {
            $this->clients = User::allClients();
            $this->employees = User::allEmployees();
            $this->notes = ProjectNotes::findOrFail($request->project_id);
            $this->noteMembers = $this->notes->member->pluck('user_id')->toArray();
            $this->askPassword = 1;
            $view = view('client.projects.notes.view', $this->data)->render();
            return Reply::successWithData('success', ['view' => $view]);
        } else {
            return Reply::error(__('messages.incorrectPassword'));
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        ProjectNotes::destroy($id);

        return Reply::success(__('messages.notesDeleted'));
    }
}
