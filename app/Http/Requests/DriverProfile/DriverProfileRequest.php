<?php

namespace App\Http\Requests\DriverProfile;

use App\Helpers\ValidationFormatter;
use App\Rules\CustomerName;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class DriverProfileRequest extends FormRequest {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [
            'driverFirstName' => ['required', 'max:15','min:2', new CustomerName],
            'driverLastName' => ['required', 'max:20','min:2', new CustomerName],
            'driverMiddleName' => ['max:15','min:2', new CustomerName],
            'driverEmail' => 'required|email:rfc,dns',
            'gender'      => 'required|in:M,F,T,N',
            'dob'         => 'required|date_format:Y-m-d',
//            'socialInsuranceNumber' => 'required|regex:/^\d{3}-\d{3}-\d{3}$/',
            'legallyPermittedToWork' => 'required|boolean'
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void|\Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator) {
        return ValidationFormatter::formatter($validator);
    }

}
