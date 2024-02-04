<?php


namespace App\Services;


use Illuminate\Http\Request;

class DeviceService
{
    /**
     * Validate Device Information from header field
     *
     * @param  Request  $request
     * @return array|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Translation\Translator|string|null
     */
    public function validateDeviceInfo(Request $request)
    {
        $deviceInfoKey = config('needs.device-info-key');
        $headerValidationErrors = [];
        if (!$request->header($deviceInfoKey)){
            $headerValidationErrors[$deviceInfoKey] = trans('custom.deviceInfoMissing');
            return $headerValidationErrors;
        }

        if(!$this->isJson($request->header($deviceInfoKey)))
        {
            return trans('messages.E_REQUEST_INVALID');
        }

        if($dataValidation = $this->deviceDataValidation($request->header($deviceInfoKey)))
        {
            return $dataValidation;
        }
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

    /**
     * Validate data in Json device info
     *
     * @param  string  $deviceInfo
     * @return array|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Translation\Translator|string|null
     */
    public function deviceDataValidation(string $deviceInfo)
    {
        $deviceInfo = json_decode($deviceInfo);
        if(!$deviceInfo->deviceType || !array_key_exists($deviceInfo->deviceType, config('needs.deviceType')))
        {
            return trans('messages.E_UNSUPPORTED_DEVICE');
        }

        if(!$deviceInfo->deviceToken)
        {
            return trans('messages.E_UNSUPPORTED_DEVICE');
        }

        if(!$deviceInfo->deviceUuid)
        {
            return trans('messages.E_UNSUPPORTED_DEVICE');
        }
    }


}
