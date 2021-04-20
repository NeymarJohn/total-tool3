<?php

namespace App;

use App\Observers\ExpenseObserver;
use App\Traits\CustomFieldsTrait;
use Illuminate\Database\Eloquent\Model;

class Expense extends BaseModel
{
    use CustomFieldsTrait;
    
    protected $dates = ['purchase_date', 'purchase_on'];

    protected $appends = ['total_amount', 'purchase_on', 'bill_url'];

    protected static function boot()
    {
        parent::boot();
        static::observe(ExpenseObserver::class);
    }

    public function getBillUrlAttribute()
    {
        return ($this->bill) ? asset_url_local_s3('expense-invoice/'.$this->bill) : '';
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withoutGlobalScopes(['active']);
    }

    public function recurrings()
    {
        return $this->hasMany(Expense::class, 'parent_id');
    }


    public function getTotalAmountAttribute()
    {

        if (!is_null($this->price) && !is_null($this->currency_id)) {
            return $this->currency->currency_symbol . $this->price;
        }

        return "";
    }

    public function getPurchaseOnAttribute()
    {
        if (!is_null($this->purchase_date)) {
            return $this->purchase_date->format('d M, Y');
        }
        return "";
    }
}
