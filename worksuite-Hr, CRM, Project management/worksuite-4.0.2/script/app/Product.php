<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends BaseModel
{
    protected $table = 'products';

    protected $fillable = ['name', 'price', 'description', 'taxes'];
    protected $appends = ['total_amount'];


    public function tax()
    {
        return $this->belongsTo(Tax::class);
    }

    public static function taxbyid($id)
    {
        return Tax::where('id', $id);
    }

    public function getTotalAmountAttribute()
    {

        if (!is_null($this->price) && !is_null($this->tax)) {
            return $this->price + ($this->price * ($this->tax->rate_percent / 100));
        }

        return "";
    }
}
