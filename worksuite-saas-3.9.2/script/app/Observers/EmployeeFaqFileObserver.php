<?php

namespace App\Observers;


use App\EmployeeFaqFile;

class EmployeeFaqFileObserver
{

    public function saving(EmployeeFaqFile $employeeFaqFile)
    {
        if (company()) {
            $employeeFaqFile->company_id = company()->id;
        }
    }

}
