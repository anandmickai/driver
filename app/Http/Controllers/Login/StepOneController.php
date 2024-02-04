<?php

namespace App\Http\Controllers\Login;

use App\Http\Controllers\Controller;
use App\Http\Requests\General\ResendOtpRequest;
use App\Http\Requests\Login\StepOneRequest;
use App\Models\DriverDetail;
use App\Repositories\Services\Otp;
use App\Services\DriverService;

class StepOneController extends Controller
{
    protected $otp;
    const OTP_TYPE= 'LOG';
    protected $driver;

    public function __construct(Otp $otp, DriverService $driverService)
    {
        $this->otp = $otp;
        $this->driver = $driverService;
    }

    /**
     * @group        Login
     * Login Step One
     * This API is part of driver Login step one.
     *
     * @bodyParam    mobileNumber numeric required mobile number of the driver Example: 9123456789
     *
     * @responseFile 400 responses/Login/StepOne/400.json
     *
     * @responseFile 422 responses/Login/StepOne/422.json
     *
     * @responseFile 200 responses/Login/StepOne/200.json
     *
     * @param  StepOneRequest  $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function index(StepOneRequest $request)
    {
        $mobileNumber = (string) $request->mobileNumber;

        $driverDetails = $this->driver->getCusDetailsByMobileNumber($mobileNumber);
        if(! $driverDetails) {
            $error = ["msg" => trans('custom.userNotRegistered')];
            return response()->fail($error, 'E_UNAUTHORIZED');
        }

        $userStatus = $this->validateUserStatus($driverDetails);
        if($userStatus['error'])
        {
            $error = ["msg" => $userStatus['errorMsg']];
            return response()->fail($error, 'E_UNAUTHORIZED');
        }

        $otp = $this->otp->sendOtp($mobileNumber, self::OTP_TYPE);
        if(!$otp) {
            $error = ["msg" => trans('custom.maxRegistrationTries')];
            return response()->fail($error, 'E_THROTTLE');
        }

        $response = [
            'msg' => trans('custom.otpSent')
        ];

        return response()->success($response, 'E_NO_ERRORS');
    }

    private function validateUserStatus(DriverDetail $driverDetail)
    {
        $error = true;
        switch ($driverDetail->driverStatus) {
            case config('needs.userStatus.New'):
                $errorMsg = trans('custom.registrationNotFinished');
                break;
            case config('needs.userStatus.Deleted'):
                $errorMsg = trans('custom.userAccountDeleted');
                break;
            case config('needs.userStatus.Freeze'):
                $errorMsg = trans('custom.accountFriezed');
                break;
            default:
                $error = false;
                $errorMsg = trans('custom.loginSuccess');
        }

        return ['error' => $error, 'errorMsg' => $errorMsg];
    }

    /**
     * @group Login
     * Login resend OTP
     * This API is part of driver mobile validation. If the OTP not arrived driver may request for resend the OTP using below service.
     *
     * @bodyParam mobileNumber numeric required mobile number of the driver Example: 9123456789
     *
     * @responseFile 422 responses/Login/ResendOtp/422.json
     *
     * @responseFile 429 responses/429.json
     *
     * @responseFile 200 responses/Login/ResendOtp/200.json
     *
     * @param  ResendOtpRequest  $request
     * @return json
     */
    public function resendLoginOtp(ResendOtpRequest $request)
    {
        $mobileNumber = (string) $request->mobileNumber;

        $checkDriver = $this->driver->getActiveDriverByMobileNumber($mobileNumber);
        if(!$checkDriver){
            $error = ["msg" => trans('custom.userNotRegistered')];
            return response()->fail($error, 'E_UNAUTHORIZED');
        }

        $resend = $this->otp->reSendOtp($mobileNumber, self::OTP_TYPE);
        if($resend['errorCode'] != 'E_OTP_SENT') {
            return response()->fail(['msg' => $resend['msg']], $resend['errorCode']);
        }

        return response()->success(['msg' => $resend['msg']], 'E_NO_ERRORS');
    }
}
