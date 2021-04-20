<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RecurringInvoiceItems extends BaseModel
{
    protected $table = 'invoice_recurring_items';
    protected $guarded = ['id'];

    public static function taxbyid($id) {
        return Tax::where('id', $id);
    }

}
