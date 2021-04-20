<?php

namespace App;

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
    protected $appends = ['doc_url'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getDocUrlAttribute()
    {
        return asset_url_local_s3('employee-docs/'.$this->user_id.'/'.$this->hashname);
    }
}
