<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DashboardWidget extends BaseModel
{
    protected $fillable = ['widget_name', 'status', 'dashboard_type'];
}
