<?php

namespace App\Http\Requests\General;

use App\Helpers\ValidationFormatter;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class AddVehicleDetailsRequest extends FormRequest
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
            'driverVehicleNumber' => 'required|string',
            'vehicleBrandDetails' => 'required|string',
            'vehicleColor'        => 'required|regex:/[a-zA-Z0-9\s]+/',
            'vehicleModel'        => 'required|regex:/[a-zA-Z0-9\s]+/',
            'vehicleYear'         => 'required|date_format:Y',
            'vehicleTypeId'       => 'required|integer|exists:reporting.vehicle_type,vehicleTypeId',
            'vehicleBodyTypeId'   => 'required|integer|exists:reporting.body_types,bodyTypeId',
            'vehicleMileage'      => 'required|numeric|min:1',
            'vehicleMileageType'  => 'required|in:km,mi',
            'vehicleImages'       => 'required',
            'vehicleImages.*'     => 'required|max:2048|mimes:jpeg,png,jpg,pdf',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'vehicleColor.regex'           => 'The :attribute must only contain alpha numerics with spaces.',
            'vehicleBodyTypeId.regex'      => 'The :attribute must only contain alpha numerics with spaces.',
            'vehicleModel.regex'           => 'The :attribute must only contain alpha numerics with spaces.',
            'vehicleBrandDetails.required' => 'The make field is required.',
            'driverVehicleNumber.required' => 'The License Plate number field is required.',
            'vehicleMileage.required'      => 'The ODOMETER reading field is required.',
            'vehicleMileage.numeric'       => 'The ODOMETER reading must only contain alpha numerics.',
            'vehicleMileage.min'           => 'The ODOMETER reading must be at least :min.',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @return void|\Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        return ValidationFormatter::formatter($validator);
    }
}
