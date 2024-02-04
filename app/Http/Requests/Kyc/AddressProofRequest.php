<?php

namespace App\Http\Requests\Kyc;

use App\Helpers\ValidationFormatter;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class AddressProofRequest extends FormRequest
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
            'documentCategoryId' => 'required|max:250',
            'file'               => 'required',
            'file.*'             => 'required|max:2048|mimes:jpeg,png,jpg,pdf',
            'addressLine1'       => 'required|min:3|max:255',
            'addressLine2'       => 'min:3|max:255',
            'city'               => 'required|min:3|max:255',
            'province'           => 'required|min:3|max:255',
            'postalCode'         => 'required|min:3|max:6'
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
