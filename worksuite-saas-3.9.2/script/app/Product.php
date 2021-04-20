<?php

namespace App;

use App\Observers\ProductObserver;
use App\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Product extends BaseModel
{
    protected $table = 'products';

    protected $fillable = ['name', 'price', 'tax_id'];
    protected $appends = ['total_amount'];

    protected static function boot()
    {
        parent::boot();

        static::observe(ProductObserver::class);

        static::addGlobalScope(new CompanyScope);
    }

    public function tax()
    {
        return $this->belongsTo(Tax::class);
    }

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }
    public function subcategory()
    {
        return $this->belongsTo(ProductSubCategory::class, 'sub_category_id');
    }

    public static function taxbyid($id) {
        return Tax::where('id', $id);
    }

    public function getTotalAmountAttribute(){

        if(!is_null($this->price) && !is_null($this->tax)){
            return $this->price + ($this->price * ($this->tax->rate_percent/100));
        }

        return "";
    }
}
