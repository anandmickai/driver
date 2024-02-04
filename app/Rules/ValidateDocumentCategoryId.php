<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidateDocumentCategoryId implements Rule
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
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return \App\Models\DriverTypeDocument::select('driverTypeDocumentId')
            ->where('documentCategoryId', $value)
            ->where('driverTypeDocumentStatus', 'A')->first();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.invalid');
    }
}
