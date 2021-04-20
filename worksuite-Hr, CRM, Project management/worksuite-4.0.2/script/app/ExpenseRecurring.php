<?php

namespace App;

use App\Observers\ExpenseRecurringObserver;
use App\Traits\CustomFieldsTrait;

class ExpenseRecurring extends BaseModel
{
    use CustomFieldsTrait;
    
    protected $dates = ['created_at'];

    protected $appends = ['total_amount', 'created_on', 'bill_url'];

    protected $table = 'expenses_recurring';

    protected static function boot()
    {
        parent::boot();
        static::observe(ExpenseRecurringObserver::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withoutGlobalScopes(['active']);
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by')->withoutGlobalScopes(['active']);
    }

    public function category()
    {
        return $this->belongsTo(ExpensesCategory::class, 'category_id');
    }

    public function recurrings()
    {
        return $this->hasMany(Expense::class, 'expenses_recurring_id');
    }


    public function getTotalAmountAttribute()
    {

        if (!is_null($this->price) && !is_null($this->currency_id)) {
            return $this->currency->currency_symbol . $this->price;
        }

        return "";
    }

    public function getCreatedOnAttribute()
    {
        if (!is_null($this->created_at)) {
            return $this->created_at->format('d M, Y');
        }
        return "";
    }

    public function getBillUrlAttribute()
    {
        return ($this->bill) ? asset_url('expense-invoice/' . $this->bill) : "";
    }
}
