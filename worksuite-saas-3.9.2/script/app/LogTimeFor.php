<?php
namespace App;

use App\Observers\LogTimeForObserver;
use App\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
/**
 * Class Holiday
 * @package App\Models
 */
class LogTimeFor extends BaseModel
{
    // Don't forget to fill this array
    protected $fillable = [];

    protected $guarded = ['id'];
    protected $table = 'log_time_for';

    protected static function boot()
    {
        parent::boot();

        static::observe(LogTimeForObserver::class);

        static::addGlobalScope(new CompanyScope);
    }

}
