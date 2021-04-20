<?php

namespace App\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class CheckAfterDate implements Rule
{
    protected $parameters;
    protected $format;
    protected $paraValues;
    protected $message;

    public function __construct($parameters,$format,$paraValues=null,$message=null)
    {
        $this->parameters   = $parameters;
        $this->format       = $format;
        $this->value        = $paraValues;
        $this->message      = $message;
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
        $parameterValue     =  ($this->paraValues) ? $this->changeDateFormat($this->paraValues) : $this->changeDateFormat(request()->{$this->parameters});
        $currentValueDate   =  $this->changeDateFormat($value);
        $compareToDate      =  $parameterValue;

        if($compareToDate->greaterThan($currentValueDate)){
            return true;
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
        return $this->checkCustomMessage();
    }

    public function changeDateFormat($value)
    {
        return Carbon::createFromFormat($this->format,  $value);
    }
    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function checkCustomMessage()
    {
        if($this->message){
            return $this->message;
        }
        elseif($this->paraValues){
            return __('messages.check_after_validation', ['date' => $this->paraValues]);
        }else
        {
            return __('messages.check_after_validation', ['date' => request()->{$this->parameters}]);
        }
    }
}