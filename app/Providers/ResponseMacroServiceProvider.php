<?php

namespace App\Providers;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class ResponseMacroServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        /*
         * All success cases with data should pass through macro "success"
         */
        Response::macro('success', function ($data = [], $code = 'E_NO_ERRORS') {
            $message = trans('messages.' . $code);
            $response = [
                'status' => 'success',
                'message' => $message['message']??null,
                'exceptionCode' => $code,
                'data' => $data
            ];
            return Response::make($response, $message['httpCode']);
        });
        /*
         * All application failure cases with data should pass through macro "fail" including validation errors
         */
        Response::macro('fail', function ($data = [], $code = 'E_VALIDATION') {
            $message = trans('messages.' . $code);
            $response = [
                'status' => 'failure',
                'message' => $message['message']??null,
                'exceptionCode' => $code,
                'trace' => (array_key_exists('httpCode', $data)) ? ['error' => $data['message']] : $data
            ];
//            \Log::channel('slack')->critical($response['trace']);
            throw new HttpResponseException(response()->json($response, $message['httpCode']));
        });
        /*
         * All application error cases with or without data should pass through macro "error"
         * mostly the APP / System errors
         */
        Response::macro('error', function ($data = [], $code = 'E_SYSTEM') {
            $message = trans('messages.' . $code);
            $response = [
                'status' => 'failure',
                'message' => $message['message']??null,
                'exceptionCode' => $code,
                'trace' => (array_key_exists('httpCode', $data)) ? ['error' => $data['message']] : $data
            ];
//            \Log::channel('slack')->critical($response['trace']);
            throw new HttpResponseException(response()->json($response, $message['httpCode']));
        });
    }
}
