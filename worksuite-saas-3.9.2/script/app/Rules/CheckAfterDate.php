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
    protected $seconValue;
    public function __construct($parameters,$format,$paraValues=null,$message=null,$seconValue=null)
    {
        $this->parameters   = $parameters;
        $this->format       = $format;
        $this->paraValues   = $paraValues;
        $this->message      = $message;
        $this->seconValue      = $seconValue;
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
        $currentValueDate   = ($this->seconValue) ? $this->changeDateFormat($this->seconValue):$this->changeDateFormat($value);
      
        $compareToDate      =  $parameterValue;

        if(!is_null($compareToDate) && !is_null($currentValueDate) && $compareToDate !== false && $currentValueDate  !== false) {
            if ($currentValueDate->greaterThan($compareToDate)) {
                return true;
            }
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
        if($value){
            try {
                return Carbon::createFromFormat($this->format,  $value);
            } catch (\Throwable $th) {
                return false;
            }
        }
        return false;
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
            return __('messages.check_equal_after_validation', ['date' => $this->paraValues]);
        }else
        {
            return str_replace('_', ' ', __('messages.check_equal_after_validation', ['date' => request()->{$this->parameters}]));
        }
    }

}