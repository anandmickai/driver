<?php

namespace App\Http\Requests\Bank;

use App\Helpers\ValidationFormatter;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class AddBankRequest extends FormRequest {

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
            'bankNumber'    => 'required|numeric',
            'transitNumber' => 'required|numeric',
            'accountNumber' => 'required|numeric',
            'bankName'      => 'required|min:3|max:45',
            'accountName'   => 'required|min:2|max:100',
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
