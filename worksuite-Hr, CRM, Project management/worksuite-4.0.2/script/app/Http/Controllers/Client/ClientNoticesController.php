<?php

namespace App\Http\Controllers\Client;

use App\Helper\Reply;
use App\Http\Controllers\Client\ClientBaseController;
use App\Http\Requests\Notice\StoreNotice;
use App\Notice;
use App\Notifications\NewNotice;
use App\User;
use Carbon\Carbon;
use http\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Yajra\DataTables\Facades\DataTables;

class ClientNoticesController extends ClientBaseController
{
    public function __construct() {
        parent::__construct();
        $this->pageTitle = 'app.menu.noticeBoard';
        $this->pageIcon = 'ti-layout-media-overlay';
        $this->middleware(function ($request, $next) {
            if (!in_array('notices',$this->user->modules)) {
                abort(403);
            }
            return $next($request);
        });
    }

    public function index() {
        $this->notices = Notice::orderBy('id', 'desc')->where('to', 'client')->limit(10)->get();
        return view('client.notices.index', $this->data);
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

        $readUser = $this->notice->member->filter(function ($value, $key) {
            return $value->user_id == $this->user->id && $value->notice_id == $this->notice->id;
        })->first();

        if ($readUser) {
            $readUser->read = 1;
            $readUser->save();
        }
        return view('client.notices.show', $this->data);
    }

    public function data(Request $request)
    {
        $notice = Notice::select('id','heading', 'created_at')->where('to', 'client');
        if ($request->startDate !== null && $request->startDate != 'null' && $request->startDate != '') {
            $startDate = Carbon::createFromFormat($this->global->date_format, $request->startDate)->toDateString();
            $notice = $notice->where(DB::raw('DATE(notices.`created_at`)'), '>=', $startDate);
        }

        if ($request->endDate !== null && $request->endDate != 'null' && $request->endDate != '') {
            $endDate = Carbon::createFromFormat($this->global->date_format, $request->endDate)->toDateString();
            $notice = $notice->where(DB::raw('DATE(notices.`created_at`)'), '<=', $endDate);
        }

        $notice = $notice->get();

        return DataTables::of($notice)
            ->addColumn('action', function ($row) {
                $action = '';

                $action .= ' <a href="javascript:showNoticeModal(' . $row->id . ')" class="btn btn-success btn-circle"
                  data-toggle="tooltip" data-original-title="View Details"><i class="fa fa-search" aria-hidden="true"></i></a>';

                return $action;
            })
            ->editColumn(
                'created_at',
                function ($row) {
                    return Carbon::parse($row->created_at)->format($this->global->date_format);
                }
            )
            ->make(true);
    }
}
