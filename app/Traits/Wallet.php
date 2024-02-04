<?php


namespace App\Traits;


use Illuminate\Support\Facades\Http;

trait Wallet
{

    /**
     * Prepare header array
     * @return string[]
     */
    private function prepareHeaders()
    {
        return [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];
    }

    /**
     * Make post request
     * @param $postParams
     * @param $endpoint
     * @return object
     */
    public function postMethod($postParams, $endpoint)
    {
        $url = config('needs.walletDetails.url');
        $walletUrl = $url.$endpoint;
        try {
            $response = Http::withHeaders($this->prepareHeaders())
                ->post($walletUrl, $postParams);
            \Log::info($response);
        } catch (\Exception $exception) {
            return $this->makeErrorResponse('E_SYSTEM', $exception->getMessage());
        }

        //validate response
        return $this->checkApiResponse($response);
    }

    /**
     * check the API response
     * @param $response
     * @return object
     */
    private function checkApiResponse($response)
    {
        if ($response->successful()) {
            return $this->parseApiResponse($response->body());
        } else {
            \Log::error($response);
            $errorCode = ($response->clientError()) ? 'E_NO_API_FOUND' : 'E_API_ERROR';
            return $this->makeErrorResponse($errorCode, trans('custom.somethingWentWrong'));
        }
    }

    /**
     * parse the out and validate
     * @param $response
     * @return object
     */
    private function parseApiResponse($response)
    {
        if ($this->jsonValidator($response)) {
            return json_decode($response);
        }
        return $this->makeErrorResponse('E_INVALID_RESPONSE', trans('custom.validJson'));
    }

    /**
     * JSON Validator function
     * @param  null  $response
     * @return bool
     */
    private function jsonValidator($response = null)
    {
        if (!empty($response)) {
            @json_decode($response);
            return (json_last_error() === JSON_ERROR_NONE);
        }
        return false;
    }

    /**
     * Make common Error response
     * @param  string  $errorCode
     * @param  null  $message
     * @return object
     */
    private function makeErrorResponse($errorCode = 'E_API_ERROR', $message = null)
    {
        return (object)[
            'status' => "failure",
            'exceptionCode' => $errorCode,
            'message' => $message
        ];
    }

}
