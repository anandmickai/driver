<?php

namespace App\Http\Requests\Registration;

use App\Helpers\ValidationFormatter;
use App\Rules\Mobilenumber;
use App\Rules\ValidateDriverUnique;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class StepTwo extends FormRequest
{
    /**
     * @var mixed
     */
    private $mobileNumber;

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
            'mobileNumber'=> ['required', 'numeric', new Mobilenumber, new ValidateDriverUnique],
            'otpNumber' => 'required|digits:'.config('needs.otpLength')
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
