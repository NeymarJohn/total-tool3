<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\NoticeBoardDataTable;
use App\EmployeeDetails;
use App\Helper\Reply;
use App\Http\Requests\Notice\StoreNotice;
use App\Notice;
use App\Notifications\NewNotice;
use App\Team;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class ManageNoticesController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.noticeBoard';
        $this->pageIcon = 'ti-layout-media-overlay';
        $this->middleware(function ($request, $next) {
            if (!in_array('notices', $this->user->modules)) {
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
    public function index(NoticeBoardDataTable $dataTable)
    {
        return $dataTable->render('admin.notices.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->teams = Team::all();
        return view('admin.notices.create', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreNotice $request)
    {
        $notice = new Notice();
        $notice->heading = $request->heading;
        $notice->description = $request->description;
        $notice->to = $request->to;
        $notice->department_id = $request->team_id;
        $notice->save();

        return Reply::redirect(route('admin.notices.index'), __('messages.noticeAdded'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->notice = Notice::with('member', 'member.user')->findOrFail($id);

        $readUser =  $this->notice->member->filter(function ($value, $key) {
            return $value->user_id == $this->user->id && $value->notice_id == $this->notice->id;
        })->first();

        if($readUser){
            $readUser->read = 1;
            $readUser->save();
        }

        $this->readMembers =  $this->notice->member->filter(function ($value, $key) {
            return $value->read == 1;
        });

        $this->unReadMembers =  $this->notice->member->filter(function ($value, $key) {
            return $value->read == 0;
        });



        return view('admin.notices.show', $this->data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->notice = Notice::findOrFail($id);
        $this->teams = Team::all();
        return view('admin.notices.edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreNotice $request, $id)
    {
        $notice = Notice::findOrFail($id);
        $notice->heading = $request->heading;
        $notice->description = $request->description;
        $notice->to = $request->to;
        $notice->department_id = $request->team_id;
        $notice->save();

        return Reply::redirect(route('admin.notices.index'), __('messages.noticeUpdated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Notice::destroy($id);
        return Reply::success(__('messages.noticeDeleted'));
    }

    public function export($startDate, $endDate)
    {

        $notice = Notice::select('id', 'heading', 'created_at');
        if ($startDate !== null && $startDate != 'null' && $startDate != '') {
            $startDate = Carbon::createFromFormat($this->global->date_format, $startDate)->toDateString();
            $notice = $notice->where(DB::raw('DATE(notices.`created_at`)'), '>=', $startDate);
        }

        if ($endDate !== null && $endDate != 'null' && $endDate != '') {
            $endDate = Carbon::createFromFormat($this->global->date_format, $endDate)->toDateString();
            $notice = $notice->where(DB::raw('DATE(notices.`created_at`)'), '<=', $endDate);
        }

        $attributes =  ['created_at'];

        $notice = $notice->get()->makeHidden($attributes);

        // Initialize the array which will be passed into the Excel
        // generator.
        $exportArray = [];

        // Define the Excel spreadsheet headers
        $exportArray[] = ['ID', 'Notice', 'Date'];

        // Convert each member of the returned collection into an array,
        // and append it to the payments array.
        foreach ($notice as $row) {
            $exportArray[] = $row->toArray();
        }

        // Generate and return the spreadsheet
        Excel::create('notice', function ($excel) use ($exportArray) {

            // Set the spreadsheet title, creator, and description
            $excel->setTitle('Notice');
            $excel->setCreator('Worksuite')->setCompany($this->companyName);
            $excel->setDescription('notice file');

            // Build the spreadsheet, passing in the payments array
            $excel->sheet('sheet1', function ($sheet) use ($exportArray) {
                $sheet->fromArray($exportArray, null, 'A1', false, false);

                $sheet->row(1, function ($row) {

                    // call row manipulation methods
                    $row->setFont(array(
                        'bold'       =>  true
                    ));
                });
            });
        })->download('xlsx');
    }
}
