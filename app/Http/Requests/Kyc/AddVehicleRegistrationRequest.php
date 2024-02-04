<?php

namespace App\Http\Requests\Kyc;

use App\Helpers\ValidationFormatter;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class AddVehicleRegistrationRequest extends FormRequest
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
            'vehicleVINNumber' => 'required|alpha_num',
            'vehicleRegistration' => 'required',
            'vehicleRegistration.*' => 'required|max:2048|mimes:jpeg,png,jpg,pdf',
        ];
    }

    public function messages()
    {
        return [
            'vehicleVINNumber.required' => 'The vehicle VIN number field is required',
            'vehicleVINNumber.alpha_num' => 'The vehicle VIN number may only contain letters and numbers'
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
