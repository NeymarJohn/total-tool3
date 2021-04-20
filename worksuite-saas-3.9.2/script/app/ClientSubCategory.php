<?php

namespace App;

use App\Observers\ClientSubCategoryObserver;
use App\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Model;

class ClientSubCategory extends BaseModel
{
    protected $table = 'client_sub_categories';

    protected static function boot()
    {
        parent::boot();
        static::observe(ClientSubCategoryObserver::class);
        static::addGlobalScope(new CompanyScope());
    }
   
    public function client_category()
    {
        return $this->belongsTo(ClientCategory::class, 'category_id');
    }
}
