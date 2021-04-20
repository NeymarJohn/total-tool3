<?php

namespace App\Observers;


use App\EmployeeFaqCategory;

class EmployeeFaqCategoryObserver
{

    public function saving(EmployeeFaqCategory $employeeFaqCategory)
    {
        if (company()) {
            $employeeFaqCategory->company_id = company()->id;
        }
    }

}
