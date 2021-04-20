<?php

namespace App\Observers;


use App\EmployeeFaq;

class EmployeeFaqObserver
{

    public function saving(EmployeeFaq $employeeFaq)
    {
        if (company()) {
            $employeeFaq->company_id = company()->id;
        }
    }

}
