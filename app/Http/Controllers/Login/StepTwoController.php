<?php

namespace App\Http\Controllers\Login;

use App\Helpers\Push;
use App\Http\Controllers\Controller;
use App\Http\Requests\Login\StepTwoRequest;
use App\Repositories\Services\Otp;
use App\Services\DriverLoginHistoryService;
use App\Services\DriverService;

class StepTwoController extends Controller {

    protected $otp;

    const OTP_TYPE = 'LOG';

    protected $driver;
    protected $loginHistory;

    public function __construct(
    Otp $otp, DriverService $driverService, DriverLoginHistoryService $driverLoginHistoryService
    ) {
        $this->otp          = $otp;
        $this->driver       = $driverService;
        $this->loginHistory = $driverLoginHistoryService;
    }

    /**
     * @group Login
     * Login Step Two
     * This API is part of driver registration step two.
     *
     * @bodyParam   mobileNumber numeric required mobile number of the driver Example: 9123456789
     * @bodyParam   otpNumber numeric required Otp shared on register mobile number Example: 1234
     *
     * @responseFile 408 responses/Login/StepTwo/408.json
     *
     * @responseFile 422 responses/Login/StepTwo/422.json
     *
     * @responseFile 200 responses/Login/StepTwo/200.json
     *
     * @param  StepTwoRequest  $request
     * @return json
     */
    public function index(StepTwoRequest $request) {
        $mobileNumber = (string) $request->mobileNumber;

        $otpValidate = $this->otp->validateOtp($request->otpNumber, $mobileNumber, self::OTP_TYPE);
        if ($otpValidate) {
            $error = ["msg" => $otpValidate['msg']];
            return response()->fail($error, $otpValidate['errorCode']);
        }

        $driverDetails = $this->driver->getActiveDriverByMobileNumber($mobileNumber);

        $request->merge([
            'driverId' => $driverDetails->driverDetailId
        ]);
        $this->loginHistory->insertLoginInfo($request);

        if (!$token = auth()->login($driverDetails)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        Push::sendPushNotification($driverDetails->driverDetailId, __('push.login', ['name' => $driverDetails->full_name]));

        $response = self::respondWithToken($token);

        return response()->success($response, 'E_NO_ERRORS');
    }

    /**
     * Get the token array structure.
     *
     * @param $token
     * @return array
     */
    protected function respondWithToken($token) {
        return [
            'msg'          => trans('custom.regSuccess'),
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth()->factory()->getTTL() * 60
        ];
    }

}
