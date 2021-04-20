<?php

namespace App;

use App\Observers\InvoiceObserver;
use App\Traits\CustomFieldsTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class Invoice extends BaseModel
{
    use Notifiable;
    use CustomFieldsTrait;

    protected $dates = ['issue_date', 'due_date'];
    protected $appends = ['total_amount', 'issue_on', 'original_invoice_number'];

    protected static function boot()
    {
        parent::boot();
        static::observe(InvoiceObserver::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id')->withTrashed();
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id')->withoutGlobalScopes(['active']);
    }

    public function clientdetails()
    {
        return $this->belongsTo(ClientDetails::class, 'client_id', 'user_id');
    }

    public function credit_notes()
    {
        return $this->belongsToMany(CreditNotes::class)->withPivot('id', 'date', 'credit_amount')->withTimestamps();
    }
    public function recurrings()
    {
        return $this->hasMany(Invoice::class, 'parent_id');
    }

    public function items()
    {
        return $this->hasMany(InvoiceItems::class, 'invoice_id');
    }

    public function payment()
    {
        return $this->hasMany(Payment::class, 'invoice_id')->orderBy('paid_on', 'desc');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function estimate()
    {
        return $this->belongsTo(Estimate::class, 'estimate_id');
    }

    public static function clientInvoices($clientId)
    {
        return Invoice::join('projects', 'projects.id', '=', 'invoices.project_id')
            ->select('projects.project_name', 'invoices.*')
            ->where('projects.client_id', $clientId)
            ->get();
    }

    public static function lastInvoiceNumber()
    {
        $invoice = DB::select('SELECT MAX(CAST(`invoice_number` as UNSIGNED)) as invoice_number FROM `invoices`');
        return $invoice[0]->invoice_number;
    }

    public function appliedCredits()
    {
        return $this->credit_notes()->sum('credit_amount');
    }

    public function amountDue()
    {
        $due = $this->total - ($this->amountPaid());
        if ($due < 0) {
            return 0;
        }
        return $due;
    }

    public function amountPaid()
    {
        return $this->payment->sum('amount') + $this->credit_notes()->sum('credit_amount');
    }

    public function getPaidAmount()
    {
        return Payment::where('invoice_id', $this->id)->sum('amount');
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

    public function getOriginalInvoiceNumberAttribute()
    {
        $invoiceSettings = invoice_setting();
        $zero = '';
        if (strlen($this->attributes['invoice_number']) < $invoiceSettings->invoice_digit) {
            for ($i = 0; $i < $invoiceSettings->invoice_digit - strlen($this->attributes['invoice_number']); $i++) {
                $zero = '0' . $zero;
            }
        }
        $zero = '#' . $zero . $this->attributes['invoice_number'];
        return $zero;
    }

    public function getInvoiceNumberAttribute($value)
    {
        if (!is_null($value)) {
            $invoiceSettings = invoice_setting();
            $zero = '';
            if (strlen($value) < $invoiceSettings->invoice_digit) {
                for ($i = 0; $i < $invoiceSettings->invoice_digit - strlen($value); $i++) {
                    $zero = '0' . $zero;
                }
            }
            $zero = $invoiceSettings->invoice_prefix . '#' . $zero . $value;
            return $zero;
        }
        return "";
    }
}
