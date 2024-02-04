<?php


namespace App\Helpers;

/**
 * Class ValidationFormatter
 * @package App\Helpers
 */
class ValidationFormatter
{
    /**
     * Just to formate the validation errors as i expect.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return mixed
     */
    public static function formatter($validate)
    {
        $validationErrors = [];
        $validatorResponse = (array)$validate->errors()->messages();
        foreach ($validatorResponse as $key => $error) {
            $validationErrors[$key] = $error[0];
        }
        return response()->fail($validationErrors);
    }
}
