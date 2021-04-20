<?php
namespace App;

use App\Observers\HolidayObserver;
use App\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Class Holiday
 * @package App\Models
 */
class Holiday extends BaseModel
{
    // Don't forget to fill this array
    protected $fillable = [];

    protected $guarded = ['id'];
    protected $dates = ['date'];

    protected static function boot()
    {
        parent::boot();

        static::observe(HolidayObserver::class);

        static::addGlobalScope(new CompanyScope);
    }


    public static function getHolidayByDates($startDate, $endDate){

        return Holiday::select(DB::raw('DATE_FORMAT(date, "%Y-%m-%d") as holiday_date'), 'occassion')->where('date', '>=', $startDate)->where('date', '<=', $endDate)->get();
    }

    public static function checkHolidayByDate($date){
        return Holiday::Where('date', $date)->first();
    }
}
