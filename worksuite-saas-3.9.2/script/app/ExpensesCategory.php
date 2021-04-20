<?php

namespace App;

use App\Observers\ExpensesCategoryObserver;
use App\Scopes\CompanyScope;
use Froiden\RestAPI\ApiModel;

class ExpensesCategory extends ApiModel
{
    protected $table = 'expenses_category';
    protected $default = ['id','category_name'];

    protected static function boot()
    {
        parent::boot();
        static::observe(ExpensesCategoryObserver::class);
        static::addGlobalScope(new CompanyScope);
    }

    public function expense()
    {
        return $this->hasMany(Expense::class);
    }
}
