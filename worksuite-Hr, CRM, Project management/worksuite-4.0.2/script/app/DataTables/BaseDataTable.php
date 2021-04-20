<?php

namespace App\DataTables;

use App\Product;
use App\Setting;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Html\Editor\Editor;

class BaseDataTable extends DataTable
{
    protected $global;

    public function __construct()
    {
        $this->global = global_setting();
        $this->user = user();
    }
}
