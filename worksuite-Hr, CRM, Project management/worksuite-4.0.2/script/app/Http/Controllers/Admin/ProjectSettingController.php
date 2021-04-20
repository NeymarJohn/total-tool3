<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Reply;
use App\Http\Requests\ProjectSetting\UpdateProjectSetting;
use App\ProjectSetting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProjectSettingController extends AdminBaseController
{
    public function __construct() {
        parent::__construct();
        $this->pageTitle = 'app.menu.projectSettings';
        $this->pageIcon = 'icon-settings';
        $this->middleware(function ($request, $next) {
            if(!in_array('projects',$this->modules)){
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
    public function index() {
        $this->projectSetting = ProjectSetting::first();

        return view('admin.project-settings.index', $this->data);
    }

    public function menu() {

        return view('admin.menu-settings.index', $this->data);
    }

    public function update(UpdateProjectSetting $request, $id)
    {
        $projectSetting = ProjectSetting::find($id);

        if ($request->send_reminder) {
            $projectSetting->send_reminder = 'yes';
        }
        else {
            $projectSetting->send_reminder = 'no';
        }

        $projectSetting->remind_time = $request->remind_time;
        $projectSetting->remind_type = $request->remind_type;
        $projectSetting->remind_to = $request->remind_to;

        $projectSetting->save();

        return Reply::redirect(route('admin.project-settings.index'));
    }
}
