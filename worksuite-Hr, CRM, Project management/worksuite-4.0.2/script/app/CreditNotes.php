<?php

namespace App;

use App\Observers\CreditNoteObserver;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class CreditNotes extends BaseModel
{
    use Notifiable;

    protected $dates = ['issue_date', 'due_date'];
    protected $appends = ['total_amount', 'issue_on', 'cn_number', 'original_cn_number'];

    protected static function boot()
    {
        parent::boot();

        static::observe(CreditNoteObserver::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id')->withoutGlobalScopes(['active']);
    }

    public function clientdetails()
    {
        return $this->belongsTo(ClientDetails::class, 'client_id', 'user_id');
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id', 'id');
    }

    public function invoices()
    {
        return $this->belongsToMany(Invoice::class)->withPivot('id', 'credit_amount', 'date')->withTimestamps();
    }

    public function items()
    {
        return $this->hasMany(CreditNoteItem::class, 'credit_note_id');
    }

    public function payment()
    {
        return $this->hasMany(Payment::class, 'invoice_id', 'invoice_id')->orderBy('paid_on', 'desc');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public static function clientInvoices($clientId)
    {
        return CreditNotes::join('projects', 'projects.id', '=', 'credit_notes.project_id')
            ->select('projects.project_name', 'credit_notes.*')
            ->where('projects.client_id', $clientId)
            ->get();
    }

    public function getPaidAmount()
    {
        return Payment::where('invoice_id', $this->invoice_id)->where('gateway', 'Credit Note')->sum('amount');
    }

    public function creditAmountUsed()
    {
        return $this->invoices()->sum('credit_amount');
    }

    public function creditAmountRemaining()
    {
        return $this->total - $this->invoices()->sum('credit_amount');
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

    public function getOriginalCnNumberAttribute()
    {
        $invoiceSettings = invoice_setting();
        $zero = '';
        if (strlen($this->attributes['cn_number']) < $invoiceSettings->invoice_digit) {
            for ($i = 0; $i < $invoiceSettings->invoice_digit - strlen($this->attributes['cn_number']); $i++) {
                $zero = '0' . $zero;
            }
        }
        $zero = '#' . $zero . $this->attributes['cn_number'];
        return $zero;
    }

    public function getCnNumberAttribute($value)
    {
        if (!is_null($value)) {
            $invoiceSettings = invoice_setting();
            $zero = '';
            if (strlen($value) < $invoiceSettings->credit_note_digit) {
                for ($i = 0; $i < $invoiceSettings->credit_note_digit - strlen($value); $i++) {
                    $zero = '0' . $zero;
                }
            }
            $zero = $invoiceSettings->credit_note_prefix . '#' . $zero . $value;
            return $zero;
        }
        return "";
    }
}
