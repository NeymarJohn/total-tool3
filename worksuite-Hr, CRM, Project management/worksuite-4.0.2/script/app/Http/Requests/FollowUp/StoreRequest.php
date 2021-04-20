<?php

namespace App\Http\Requests\FollowUp;

use App\Http\Requests\CoreRequest;
use App\Lead;
use App\Rules\CheckDateFormat;
use App\Rules\CheckEqualAfterDate;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends CoreRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $lead = Lead::find($this->lead_id);
        if($this->has('type')){
            return [
                'next_follow_up_date' => ['required', new CheckDateFormat(null, 'd/m/Y H:i'),new CheckEqualAfterDate('','d/m/Y H:i', $lead->created_at->format('d/m/Y H:i'))],
            ];
        }
        else{
            $setting = global_setting();
            return [
                'next_follow_up_date' => ['required',new CheckDateFormat(null, $setting->date_format),new CheckEqualAfterDate('',$setting->date_format, $lead->created_at->format($setting->date_format))],
            ];
        }
    }
}
