<?php

namespace App\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class CheckDateFormat implements Rule
{
    protected $parameters;
    protected $format;
    public function __construct($parameters=null,$format=null)
    {
        $this->parameters = $parameters;
        $this->format = $format;
    }
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        try {
            $this->changeDateFormat($value);
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * @param $value
     * @return Carbon|false
     */
    public function changeDateFormat($value)
    {
        if($value)
        {
            if($this->format){
                return Carbon::createFromFormat($this->format,  $value);
            }
            return Carbon::createFromFormat('Y-m-d',  $value);
        }
       return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('messages.check_date_format', ['format' => $this->format]);
    }
}