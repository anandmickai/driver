<?php


namespace App\Services;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;

class HeadersService
{
    /**
     * Validate the request headers
     *
     * @param $request
     * @return array|bool|\Illuminate\Contracts\Translation\Translator|string|null
     */
    public function validateRequestHeader($request)
    {
        $headers = config('needs.headers');
        if ($missingHeaders = $this->checkHeaders($request, $headers)) {
            return $missingHeaders;
        }
        if (!self::validateIp($request->ip())) {
            return trans('messages.E_CLIENT_IP');
        }

        return false;
    }

    /**
     * Validate the IP address
     *
     * @param $ip
     * @return bool
     */
    public function validateIp($ip) : bool
    {
        if (!App::isLocal()) {
            return filter_var(
                $ip,
                FILTER_VALIDATE_IP,
                FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
            );
        }
        return true;
    }

    /**
     * Check the headers exists or not. if so validate the types
     *
     * @param $request
     * @param array $headers
     * @return array|bool
     */
    public function checkHeaders($request, array $headers = [])
    {
        $headerValidationErrors = [];
        foreach ($headers as $header) {
            if (!$request->header($header)) {
                $headerValidationErrors[$header] = 'Missing request definition: ' . $header;
            }
        }

        if(!$headerValidationErrors) {
            return $this->requestContentValidation($request);
        }

        return $headerValidationErrors;
    }

    /**
     * Check the content is valid as per headers
     *
     * @param $request
     * @return array|bool
     */
    public function requestContentValidation($request)
    {
        if($request->header('Content-Type') == 'application/json')
        {
            if(!$this->isJson($request->getContent()))
            {
                return trans('messages.E_REQUEST_INVALID');
            }
            return false;
        }
        elseif( Str::contains($request->url(), '/upload'))
        {
            return false;
        }

        return trans('messages.E_NOT_ACCEPTABLE');
    }

    /**
     * Check the string is JSON or not
     *
     * @param $string
     * @return bool
     */
    public function isJson($string): bool
    {
        if($string) {
            json_decode($string);
            return (json_last_error() == JSON_ERROR_NONE);
        }
        return true;
    }
}
