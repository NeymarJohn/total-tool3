<?php

namespace App;

use Froiden\RestAPI\ApiModel;

class ExpensesCategory extends ApiModel
{
    protected $table = 'expenses_category';
    protected $default = ['id','category_name'];

    public function expense()
    {
        return $this->hasMany(Expense::class);
    }
}
