<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class BookingId implements Rule {

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct() {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value) {
        $bookigId = \App\Models\BookingDetail::find($value);
        if (!$bookigId) {
            return FALSE;
        }
        return TRUE;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message() {
        return trans('validation.bookingId');
    }

}
