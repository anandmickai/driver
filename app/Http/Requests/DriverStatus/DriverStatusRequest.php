<?php

namespace App\Http\Requests\DriverStatus;

use App\Helpers\ValidationFormatter;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class DriverStatusRequest extends FormRequest {

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
            'raidStatus'  => 'required|in:A,I',
            'long'        => 'required',
            'lat'         => 'required',
            'vehicleType' => 'required'
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
