<?php
namespace App;

use Illuminate\Support\Facades\DB;

/**
 * Class Holiday
 * @package App\Models
 */
class Holiday extends BaseModel
{
    // Don't forget to fill this array
    protected $fillable = ['date','occassion'];

    protected $guarded = ['id'];
    protected $dates = ['date'];

    public static function getHolidayByDates($startDate, $endDate){
        if (!is_null($endDate)||!is_null($startDate)) {
            return Holiday::select(DB::raw('DATE_FORMAT(date, "%Y-%m-%d") as holiday_date'), 'occassion')->where('date', '>=', $startDate)->where('date', '<=', $endDate)->get();
        }
        return;
    }

    public static function checkHolidayByDate($date){
        return Holiday::Where('date', $date)->first();
    }
}
