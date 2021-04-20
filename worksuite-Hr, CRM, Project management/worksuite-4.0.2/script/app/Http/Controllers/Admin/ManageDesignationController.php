<?php

namespace App\Http\Controllers\Admin;

use App\Designation;
use App\EmployeeDetails;
use App\Helper\Reply;
use App\Http\Requests\Designation\StoreRequest;
use App\Http\Requests\Designation\UpdateRequest;

class ManageDesignationController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.designation';
        $this->pageIcon = 'icon-user';
        $this->middleware(function ($request, $next) {
            if(!in_array('employees',$this->user->modules)){
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
        $this->groups = Designation::with('members', 'members.user')->get();
        return view('admin.designation.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.designation.create', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function quickCreate()
    {
        $this->teams = Designation::all();
        return view('admin.designation.quick-create', $this->data);
    }

    /**
     * @param StoreRequest $request
     * @return array
     */
    public function store(StoreRequest $request)
    {
        $group = new Designation();
        $group->name = $request->designation_name;
        $group->save();

        return Reply::redirect(route('admin.designations.index'), __('messages.designationAdded'));
    }

    /**
     * @param StoreRequest $request
     * @return array
     */
    public function quickStore(StoreRequest $request)
    {
        $group = new Designation();
        $group->name = $request->designation_name;
        $group->save();

        $designations = Designation::all();
        $teamData = '';

        foreach ($designations as $team){
            $selected = '';

            if($team->id == $group->id){
                $selected = 'selected';
            }

            $teamData .= '<option '.$selected.' value="'.$team->id.'"> '.$team->name.' </option>';
        }

        return Reply::successWithData( __('messages.designationAdded'), ['designationData' => $teamData]);
    }

    /**
     * Display the specified resource.
     *[
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
        $this->designation = Designation::with('members', 'members.user')->findOrFail($id);
        return view('admin.designation.edit', $this->data);
    }

    /**
     * @param UpdateRequest $request
     * @param $id
     * @return array
     */
    public function update(UpdateRequest $request, $id)
    {
        $group = Designation::find($id);
        $group->name = $request->designation_name;
        $group->save();

        return Reply::redirect(route('admin.designations.index'), __('messages.updatedSuccessfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        EmployeeDetails::where('designation_id', $id)->update(['designation_id' => NULL]);
        Designation::destroy($id);
        return Reply::dataOnly(['status' => 'success']);
    }
}
