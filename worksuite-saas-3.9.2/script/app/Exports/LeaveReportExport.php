<?php

namespace App\Exports;

use App\Leave;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LeaveReportExport implements FromCollection,WithHeadings
{

    private $userId;
    private $startDate;
    private $endDate;

    public function __construct($userId,$startDate,$endDate)
    {
        $this->userId = $userId;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function headings(): array
    {
        return ['Leave Type', 'Date', 'Reason', 'Status', 'Reject Reason'];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $startDate = $this->startDate;
        $endDate = $this->endDate;
        $rows = Leave::join('leave_types', 'leave_types.id', '=', 'leaves.leave_type_id')
            ->where('leaves.user_id', $this->userId)
            ->select(
                'leave_types.type_name',
                'leaves.leave_date',
                'leaves.reason',
                'leaves.status',
                'leaves.reject_reason'
            );

        if($startDate !== null && $startDate != 'null' && $startDate != ''){
            $rows = $rows->where(DB::raw('DATE(leaves.`leave_date`)'), '>=', $startDate);
        }

        if($endDate !== null && $endDate != 'null' && $endDate != ''){
            $rows = $rows->where(DB::raw('DATE(leaves.`leave_date`)'), '<=', $endDate);
        }

       $attributes =  ['date'];
       $rows = $rows->get()->makeHidden($attributes);

       return $rows;
    }
}
