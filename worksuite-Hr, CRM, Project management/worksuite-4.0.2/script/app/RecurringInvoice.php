<?php

namespace App;

use App\Observers\InvoiceRecurringObserver;
use App\Scopes\CompanyScope;
use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;

class RecurringInvoice extends BaseModel
{
    use Notifiable;

    protected $table = 'invoice_recurring';
    protected $dates = ['issue_date', 'due_date'];
    protected $appends = ['total_amount', 'issue_on'];

    protected static function boot()
    {
        parent::boot();

        static::observe(InvoiceRecurringObserver::class);

    }

    public function recurrings()
    {
        return $this->hasMany(Invoice::class, 'invoice_recurring_id');
    }


    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id')->withoutGlobalScopes(['active']);
    }

    public function withoutGlobalScopeCompanyClient()
    {
        return $this->belongsTo(User::class, 'client_id')->withoutGlobalScopes([CompanyScope::class, 'active']);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function clientdetails()
    {
        return $this->belongsTo(ClientDetails::class, 'client_id', 'user_id');
    }


    public function items()
    {
        return $this->hasMany(RecurringInvoiceItems::class, 'invoice_recurring_id');
    }


    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id')->withoutGlobalScopes(['enable']);
    }

    public function getTotalAmountAttribute()
    {

        if (!is_null($this->total) && !is_null($this->currency_symbol)) {
            return $this->currency_symbol . $this->total;
        }

        return "";
    }

    public function getIssueOnAttribute()
    {
        if (!is_null($this->issue_date)) {
            return Carbon::parse($this->issue_date)->format('d F, Y');
        }
        return "";
    }

}
