<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\ProjectNotes\StoreNotes;
use App\Http\Requests\ProjectNotes\UpdateNotes;
use App\Project;
use App\ProjectNotes;
use App\ProjectUserNotes;
use App\User;
use App\Helper\Reply;
class AdminProjectNotesController extends AdminBaseController
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
        $timeLogs = ProjectNotes::where('project_id', $id)->get();
        return DataTables::of($timeLogs)
            ->addColumn('action', function ($row) {
                return '<a href="javascript:;" class="btn btn-info btn-circle edit-contact"
                      data-toggle="tooltip" data-contact-id="' . $row->id . '"  data-original-title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>

                      <a href="javascript:;" class="btn btn-info btn-circle view-contact"
                      data-toggle="tooltip" data-contact-id="' . $row->id . '"  data-original-title="View"><i class="fa fa-search" aria-hidden="true"></i></a>
                   
                      <a href="javascript:;" class="btn btn-danger btn-circle sa-params"
                      data-toggle="tooltip" data-contact-id="' . $row->id . '" data-original-title="Delete"><i class="fa fa-times" aria-hidden="true"></i></a>';
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
        
        return view('admin.projects.notes.show', $this->data);
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
        $this->employees = User::allEmployees()->where('id','!=',$this->user->id);
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
        $note->is_client_show = $request->is_client_show == "on" ? 1 : 0;
        $note->ask_password = $request->ask_password == "on" ? 1 : 0;
        $note->note_details = $request->note_details;
        $note->save();
        ProjectUserNotes::where('project_notes_id', $note->id)->delete();
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
    public function view($id)
    {
         $this->clients = User::allClients();
        $this->employees = User::allEmployees();
        $this->notes = ProjectNotes::findOrFail($id);
        $this->noteMembers = $this->notes->member->pluck('user_id')->toArray();
        return view('admin.projects.notes.view', $this->data);
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
