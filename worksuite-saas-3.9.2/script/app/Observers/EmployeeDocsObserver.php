<?php

namespace App\Observers;


use App\EmployeeDocs;

class EmployeeDocsObserver
{

    public function saving(EmployeeDocs $employeeDocs)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $employeeDocs->company_id = company()->id;
        }
    }

}
