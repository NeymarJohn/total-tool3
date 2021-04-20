<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Reply;
use App\Http\Requests\CommonRequest;
use App\LogTimeFor;
use Illuminate\Http\Request;

class LogTimeSettingsController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.timeLogSettings';
        $this->pageIcon = 'icon-settings';
        $this->middleware(function ($request, $next) {
            if (!in_array('timelogs', $this->modules)) {
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

        $this->data['logTime'] = LogTimeFor::first();


        return view('admin.log-time-settings.edit', $this->data);
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
    public function store(CommonRequest $request)
    {
        $logTime = LogTimeFor::first();

        if ($request->has('log_time_for')) {
            $logTime->log_time_for = $request->log_time_for;
        }
        if ($request->has('auto_timer_stop')) {
            $logTime->auto_timer_stop = $request->auto_timer_stop;
        }
        if ($request->has('approval_required')) {
            $logTime->approval_required = $request->approval_required;
        }

        $logTime->save();
        session()->forget('time_log_setting');

        return Reply::redirect(route('admin.log-time-settings.index'), __('messages.logTimeUpdateSuccess'));
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
