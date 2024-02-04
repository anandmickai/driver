<?php

namespace App\Http\Requests\Kyc;

use App\Helpers\ValidationFormatter;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class AddVehicleInsuranceRequest extends FormRequest
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
            'vehicleDetailId' => 'required|numeric',
            'insurancePolicyNum' => 'required',
            'insuranceExpiryDate' => 'required|date_format:Y-m-d',
            'vehicleInsurer' => 'required',
            'insurancePolicy' => 'required',
            'insurancePolicy.*' => 'required|max:2048|mimes:jpeg,png,jpg,pdf'
        ];
    }

    public function messages()
    {
        return [
            'insuranceExpiryDate.date_format' => 'The insurance expiry date does not match the format YYYY-mm-dd',
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
