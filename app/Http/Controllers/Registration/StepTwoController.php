<?php

namespace App\Http\Controllers\Registration;

use App\Helpers\Push;
use App\Http\Controllers\Controller;
use App\Http\Requests\Registration\StepTwo;
use App\Repositories\Services\Otp;
use App\Services\DriverLoginHistoryService;
use App\Services\DriverService;
use App\Services\WalletService;

class StepTwoController extends Controller
{
    protected $otp;
    const OTP_TYPE = 'REG';
    protected $driver;
    protected $loginHistory;
    protected $wallet;

    public function __construct(
        Otp $otp,
        DriverService $driverService,
        DriverLoginHistoryService $driverLoginHistoryService,
        WalletService $walletService
    ) {
        $this->otp = $otp;
        $this->driver = $driverService;
        $this->loginHistory = $driverLoginHistoryService;
        $this->wallet = $walletService;
    }

    /**
     * @group Registration
     * Registration Step Two
     * This API is part of driver registration step two.
     *
     * @bodyParam mobileNumber numeric required mobile number of the driver Example: 9123456789
     * @bodyParam otpNumber numeric required Otp shared on register mobile number Example: 1234
     *
     * @responseFile 408 responses/Registration/StepTwo/408.json
     *
     * @responseFile 422 responses/Registration/StepTwo/422.json
     *
     * @responseFile 200 responses/Registration/StepTwo/200.json
     *
     * @param  StepTwo  $request
     * @return json
     * @throws \Throwable
     */
    public function stepTwo(StepTwo $request)
    {
        $mobileNumber = (string) $request->mobileNumber;

        $otpValidate = $this->otp->validateOtp($request->otpNumber, $mobileNumber, self::OTP_TYPE);
        if ($otpValidate) {
            $error = ["msg" => $otpValidate['msg']];
            return response()->fail($error, $otpValidate['errorCode']);
        }

        $this->driver->updateDriverStatus($mobileNumber);
        $driverDetails = $this->driver->getActiveDriverByMobileNumber($mobileNumber);

        $this->driver->createDriverRaidStatus($driverDetails->driverDetailId);

        $request->merge([
            'driverId' => $driverDetails->driverDetailId
        ]);
        $this->loginHistory->insertLoginInfo($request);

        if (!$token = auth()->login($driverDetails)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $this->registerUserInWallet($request);

        Push::sendPushNotification($driverDetails->driverDetailId, __('push.registration', ['name' => $driverDetails->full_name]));

        $response = self::respondWithToken($token);

        return response()->success($response, 'E_NO_ERRORS');
    }

    /**
     * Get the token array structure.
     *
     * @param $token
     * @return array
     */
    protected function respondWithToken($token)
    {
        return [
            'msg' => trans('custom.regSuccess'),
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ];
    }

    public function registerUserInWallet($request)
    {
        $driver = \App\Models\DriverDetail::select('driverMobileNumber', 'driverEmail', 'driverFirstName', 'driverLastName', 'driverMiddleName')
            ->where('driverMobileNumber', $request->mobileNumber)->first();
        $params = [
            "countryCode" => 'CA',
            "mobileNumber" => $driver->driverMobileNumber,
            "emailAddress" => $driver->driverEmail,
            "customerName" => $driver->driverFirstName.' '.$driver->driverLastName,
            "customerType" => 'D'
        ];
        $this->wallet->registerWallet($params);
    }

}
