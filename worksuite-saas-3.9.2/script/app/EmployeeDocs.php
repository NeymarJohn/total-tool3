<?php

namespace App;

use App\Observers\EmployeeDocsObserver;
use App\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Holiday
 * @package App\Models
 */
class EmployeeDocs extends BaseModel
{
    // Don't forget to fill this array
    protected $fillable = [];

    protected $guarded = ['id'];
    protected $table =  'employee_docs';

    protected $appends = ['file_url'];

    protected static function boot()
    {
        parent::boot();

        static::observe(EmployeeDocsObserver::class);

        static::addGlobalScope(new CompanyScope);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getFileUrlAttribute()
    {
        return asset_url_local_s3('employee-docs/' . $this->user_id . '/' . $this->hashname);
    }
}
