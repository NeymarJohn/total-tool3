<?php

namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\ProjectNotes\StoreNotes;
use App\Http\Requests\ProjectNotes\UpdateNotes;
use App\Project;
use App\ProjectNotes;
use App\ProjectUserNotes;
use App\User;
use App\Helper\Reply;
use App\Http\Requests\VerifyPasswordRequest;
use Illuminate\Support\Facades\Hash;
class MemberProjectsNotesController extends MemberBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageIcon = 'icon-layers';
        $this->pageTitle = 'app.menu.projects';
        $this->middleware(function ($request, $next) {
            if(!in_array('projects',$this->user->modules)){
                abort(403);
            }
            return $next($request);
        });
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreNotes $request)
    {
        $note = new ProjectNotes();
        $note->notes_title = $request->notes_title;
        $note->project_id = $request->project_id;
        $note->notes_type = $request->notes_type;
        $note->client_id = $request->client_id ?? null;
        $note->is_client_show = $request->is_client_show ? $request->is_client_show : '';
        $note->ask_password = $request->ask_password ? $request->ask_password : '';
        $note->note_details = $request->note_details;
        $note->save();
        if($request->notes_type == 1){
            $users = $request->user_id;
            if(!is_null($users)){
            foreach ($users as $user) {
                
                $member = ProjectUserNotes::firstOrCreate([
                    'user_id' => $user,
                    'project_notes_id' => $note->id
                ]);
            }
        }
        }
        return Reply::success(__('messages.notesAdded'));
    }

    public function data($id)
    {
        $this->userDetail = auth()->user();
        $timeLogs = ProjectNotes::select('project_notes.notes_title','project_notes.ask_password','project_notes.notes_type','project_notes.id',
            'project_notes.note_details')->leftjoin('project_user_notes', 'project_user_notes.project_notes_id', '=', 'project_notes.id');
          
        if (!$this->user->cans('view_projects')) {
            $timeLogs = $timeLogs
            ->where('project_user_notes.user_id', '=', $this->userDetail->id)
            ->where('project_notes.project_id', $id)
            ->orwhere('project_notes.notes_type',0);
        }else{
            $timeLogs = $timeLogs
            ->where('project_notes.project_id', $id);
        }
        $timeLogs->groupBy('project_notes.id')
        ->get();
        return DataTables::of($timeLogs)
            ->addColumn('action', function ($row) {
                $button ='';
                if ( $this->user->cans('edit_projects')) {
                $button .= '<a href="javascript:;" class="btn btn-info btn-circle edit-contact"
                      data-toggle="tooltip" data-contact-id="' . $row->id . '"  data-original-title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
                }
                if ($this->user->cans('delete_projects')) {
                    $button .='<a href="javascript:;" class="btn btn-danger btn-circle sa-params" style="
                    margin-left: 3px;
                      data-toggle="tooltip" data-contact-id="' . $row->id . '" data-original-title="Delete"  ><i class="fa fa-times" aria-hidden="true"></i></a>';
                }
                if($row->ask_password != 1){
                    $button .=' <a href="javascript:;" class="btn btn-info btn-circle view-notes"
                    data-toggle="tooltip" data-contact-id="' . $row->id . '"  data-original-title="View"><i class="fa fa-search" aria-hidden="true"></i></a>';
                }else{
                    $button .=' <a href="javascript:;" class="btn btn-info btn-circle view-notes-modal"
                    data-toggle="tooltip" data-contact-id="' . $row->id . '"  data-original-title="View"><i class="fa fa-search" aria-hidden="true"></i></a>';
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
    public function askForPassword($id)
    {
        $this->client = User::findClient($id);
        $this->notes = ProjectNotes::findOrFail($id);
        return view('member.projects.notes.verify-password', $this->data);
    }
    public function checkPassword(VerifyPasswordRequest $request)
    {
        $this->client = User::findClient($request->client_id);
        if(Hash::check($request->password,$this->client->password )) {
            $this->clients = User::allClients();
            $this->employees = User::allEmployees();
            $this->notes = ProjectNotes::findOrFail($request->note_id);
            $this->noteMembers = $this->notes->member->pluck('user_id')->toArray();
            $this->askPassword = 1;
            $view = view('member.projects.notes.view', $this->data)->render();
            return Reply::successWithData('success', ['view' => $view]);
        } else {
            return Reply::error(__('messages.incorrectPassword'));
        }
    } 
    public function show($id)
    {
        $this->clients = User::allClients();
        $this->employees = User::allEmployees()->where('id','!=',$this->user->id);
        $this->project = Project::findOrFail($id);
        
        return view('member.projects.notes.show', $this->data);
    }
    public function view($id)
    {
         $this->clients = User::allClients();
        $this->employees = User::allEmployees();
        $this->notes = ProjectNotes::findOrFail($id);
        $this->noteMembers = $this->notes->member->pluck('user_id')->toArray();
        return view('member.projects.notes.view', $this->data);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
         $this->clients = User::allClients();
        $this->employees = User::allEmployees();
        $this->notes = ProjectNotes::findOrFail($id);
        $this->noteMembers = $this->notes->member->pluck('user_id')->toArray();
        return view('admin.projects.notes.edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateNotes $request, $id)
    {
        $note = ProjectNotes::findOrFail($id);
        $note->notes_title = $request->notes_title;
        $note->notes_type = $request->notes_type;
        $note->member_id = $request->member_id ?? null;
        $note->client_id = $request->client_id ?? null;
        $note->ask_password = $request->ask_password ? $request->ask_password : '';
        $note->note_details = $request->note_details;
        $note->save();
        if($request->notes_type == 1){
            $users = $request->user_id;
            if(!is_null($users)){
                foreach ($users as $user) {
                    
                    $member = ProjectUserNotes::firstOrCreate([
                        'user_id' => $user,
                        'project_notes_id' => $note->id
                    ]);
                }
             }
        }
        return Reply::success(__('messages.notesUpdated'));
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
