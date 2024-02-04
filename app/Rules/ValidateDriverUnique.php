<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidateDriverUnique implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Check user mobile number already exists with us or not
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $driver = \App\Models\DriverDetail::select('driverStatus')->where('driverMobileNumber', $value)->first();
        if(! $driver) {
            return true;
        }

        return $driver->driverStatus != config('needs.userStatus.New') ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.duplicateCustomer');
    }
}
