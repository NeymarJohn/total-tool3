<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Helper\Files;
use App\Helper\Reply;
use App\Http\Requests\SuperAdmin\SuperAdmin\CreateSuperAdmin;
use App\Http\Requests\SuperAdmin\SuperAdmin\UpdateSuperAdmin;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;

class SuperAdminController extends SuperAdminBaseController
{

    public function __construct() {
        parent::__construct();
        $this->pageTitle = 'app.superAdmin';
        $this->pageIcon = 'icon-people';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->superAdmin = User::allSuperAdmin();
        $this->totalSuperAdmin = count($this->superAdmin);

        return view('super-admin.super-admin.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($leadID = null)
    {
        $generatedBy = new User();
        return view('super-admin.super-admin.create', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateSuperAdmin $request)
    {
        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->mobile = $request->input('mobile');
        $user->login = 'enable';
        $user->status = 'active';
        $user->super_admin = '1';

        if ($request->hasFile('image')) {
            Files::deleteFile($user->image,'avatar');
            $user->image = Files::upload($request->image, 'avatar',300);
        }
        $user->save();

        return Reply::redirect(route('super-admin.super-admin.index'));
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
        $this->userDetail = User::withoutGlobalScope('active')->where('super_admin','1')
            ->findOrFail($id);

        return view('super-admin.super-admin.edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSuperAdmin $request, $id)
    {
        $user = User::withoutGlobalScope('active')
            ->where('super_admin','1')
            ->findOrFail($id);
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        if ($request->password != '') {
            $user->password = Hash::make($request->input('password'));
        }
        $user->mobile = $request->input('mobile');
        if ($this->user->id != $user->id)
        {
            $user->status = $request->input('status');
        }

        if ($request->hasFile('image')) {
            Files::deleteFile($user->image,'avatar');
            $user->image = Files::upload($request->image, 'avatar',300);
        }
        $user->save();

        return Reply::redirect(route('super-admin.super-admin.index'));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::destroy($id);
        return Reply::success(__('messages.userDeleted'));
    }

    public function data(Request $request)
    {
        $users = User::allSuperAdmin();
        return DataTables::of($users)
            ->addColumn('action', function($row){
                $action = '<a href="'.route('super-admin.super-admin.edit', [$row->id]).'" class="btn btn-info btn-circle"
                      data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a> ';
                if($this->user->id != $row->id){
                    $action .=   ' <a href="javascript:;" class="btn btn-danger btn-circle sa-params"
                      data-toggle="tooltip" data-user-id="'.$row->id.'" data-original-title="Delete"><i class="fa fa-times" aria-hidden="true"></i></a>';
                }
                return $action;

            })
            ->editColumn(
                'name',
                function ($row) {
                    return ucfirst($row->name);
                }
            )
            ->editColumn(
                'status',
                function ($row) {
                    if($row->status == 'active'){
                        return '<label class="label label-success">'.__('app.active').'</label>';
                    }
                    else{
                        return '<label class="label label-danger">'.__('app.deactive').'</label>';
                    }
                }
            )
            ->rawColumns(['name', 'action', 'status'])
            ->make(true);
    }
}
