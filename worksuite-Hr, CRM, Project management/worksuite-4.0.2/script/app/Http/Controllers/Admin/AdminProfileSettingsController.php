<?php

namespace App\Http\Controllers\Admin;

use App\Country;

class AdminProfileSettingsController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageIcon = 'icon-user';
        $this->pageTitle = 'app.menu.profileSettings';
    }

    public function index()
    {
        $this->userDetail = $this->user;
        $this->employeeDetail = $this->user->employee_details;
        $this->countries = Country::all();

        return view('admin.profile.index', $this->data);
    }
}
