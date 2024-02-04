<?php

namespace App\Http\Requests\Registration;

use App\Helpers\ValidationFormatter;
use App\Rules\CustomerName;
use App\Rules\Mobilenumber;
use App\Rules\ValidateDriverUnique;
use App\Rules\ValidateCustomerEmailUnique;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class StepOne extends FormRequest
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
        return [
            'driverFirstName' => ['required', 'max:15','min:2', new CustomerName],
            'driverLastName' => ['required', 'max:20','min:2', new CustomerName],
            'driverMiddleName' => ['max:15','min:2', new CustomerName],
            'emailAddress' => ['required','email','max:45', new ValidateCustomerEmailUnique],
            'mobileNumber' => ['required', 'numeric', new Mobilenumber, new ValidateDriverUnique],
            'countryCode' => 'required|alpha|exists:App\Models\Country,countryCode',
            'legallyPermittedToWork' => 'required|boolean'
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void|\Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        return ValidationFormatter::formatter($validator);
    }
}
