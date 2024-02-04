<?php

namespace App\Http\Requests\Kyc;

use App\Helpers\ValidationFormatter;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class DrivingLicenceRequest extends FormRequest
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
            'file' => 'required',
            'file.*' => 'required|max:2048|mimes:jpeg,png,jpg,pdf',
            'referenceNumber' => 'required|min:3|max:25|alpha_num',
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
