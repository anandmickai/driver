<?php

namespace App\Http\Controllers\Registration;

use App\Http\Controllers\Controller;
use App\Http\Requests\General\ResendOtpRequest;
use App\Http\Requests\Registration\StepOne;
use App\Models\Country;
use App\Models\DriverDetail;
use App\Repositories\Services\Otp;
use App\Services\WalletService;
use App\Traits\CustomerCheckTraits;
use App\Traits\UniqueStringTrait;
use Illuminate\Http\Request;

class StepOneController extends Controller
{
    use CustomerCheckTraits, UniqueStringTrait;

    const OTP_TYPE = 'REG';
    const CURRENT_REG_STEP = 1;
    protected $otp;
    public $wallet;

    public function __construct(Otp $otp, WalletService $walletService)
    {
        $this->otp = $otp;
        $this->wallet = $walletService;
    }

    /**
     * @group Registration
     * Registration Step One
     * This API is part of driver registration step one.
     *
     * @bodyParam mobileNumber numeric required mobile number of the driver Example: 9123456789
     * @bodyParam emailAddress string required email address of the driver Example: example@gmail.com
     * @bodyParam driverFirstName string required Name of the driver Example: Jhon Bob
     * @bodyParam driverLastName string required Name of the driver Example: Jhon Bob
     * @bodyParam driverMiddleName string required Name of the driver Example: Jhon Bob
     * @bodyParam countryCode alpha required country code Example: IND
     * @bodyParam legallyPermittedToWork string required country code Example: IND
     *
     * @responseFile 400 responses/Registration/StepOne/400.json
     *
     * @responseFile 422 responses/Registration/StepOne/422.json
     *
     * @responseFile 200 responses/Registration/StepOne/200.json
     *
     * @param  StepOne  $request
     * @return json
     * @throws \Throwable
     */
    public function stepOne(StepOne $request)
    {
        $mobileNumber = (string)$request->mobileNumber;

        $checkDriver = $this->checkMobile($mobileNumber);
        if (!$checkDriver) {
            $this->saveData($request);
        } elseif ($checkDriver->customerStatus === config('needs.userStatus.New')) {
            $this->updateData($request);
        } elseif ($checkDriver->driverStatus != config('needs.userStatus.New')) {
            $error = ["msg" => trans('custom.userAlreadyRegistered')];
            return response()->fail($error, 'E_UNAUTHORIZED');
        }

        $response = $this->wallet->getBalance($mobileNumber);
        if ($response && $response->status === 'success') {
            $error = ["msg" => trans('custom.cannotRegisteredAsDriver')];
            return response()->fail($error, 'E_NO_CONTENT');
        }

        $otp = $this->otp->sendOtp($mobileNumber, self::OTP_TYPE);
        if (!$otp) {
            $error = ["msg" => trans('custom.maxRegistrationTries')];
            return response()->fail($error, 'E_THROTTLE');
        }

        $response = [
            'msg' => trans('custom.otpSent')
        ];

        return response()->success($response, 'E_NO_ERRORS');
    }

    /**
     * Get the country id by country code
     *
     * @param  string  $countryIsdCode
     * @return mixed
     */
    private function getCountryIdByCode(string $countryIsdCode)
    {
        return Country::select('countryId')->where('countryCode', $countryIsdCode)->first()->countryId;
    }

    private function saveData($request)
    {
        $customer = new DriverDetail();
        $customer->driverFirstName = $request->driverFirstName;
        $customer->driverLastName = $request->driverLastName;
        $customer->driverMiddleName = $request->driverMiddleName;
        $customer->driverEmail = $request->emailAddress;
        $customer->driverMobileNumber = (string)$request->mobileNumber;
        $customer->registrationStep = self::CURRENT_REG_STEP;
        $customer->countryCode = $this->getCountryIdByCode($request->countryCode);
        $customer->isVerified = config('needs.customerVerified.No');
        $customer->driverSecret = $this->generateUniqueString();
        $customer->driverStatus = config('needs.userStatus.New');
        $customer->socialInsuranceNumber = $request->socialInsuranceNumber ?? '000-000-000';
        $customer->legallyPermittedToWork = $request->legallyPermittedToWork;
        $customer->saveOrFail();

        return $customer->driverDetailId;
    }

    /**
     * Update the customer information if already exsists but not active
     * @param  Request  $request
     * @return mixed
     */
    public function updateData(Request $request)
    {
        $update = [
            'driverFirstName' => $request->driverFirstName,
            'driverLastName' => $request->driverLastName,
            'driverMiddleName' => $request->driverMiddleName,
            'driverEmail' => $request->emailAddress,
            'registrationStep' => self::CURRENT_REG_STEP,
            'countryCode' => $this->getCountryIdByCode($request->countryCode),
            'isVerified' => config('needs.customerVerified.No'),
            'driverStatus' => config('needs.userStatus.New')
        ];
        return DriverDetail::where('driverMobileNumber', $request->mobileNumber)
            ->update($update);
    }

    /**
     * @group Registration
     * Registration resend OTP
     * This API is part of driver mobile validation. If the OTP not arrived driver may request for resend the OTP using below service.
     *
     * @bodyParam mobileNumber numeric required mobile number of the driver Example: 9123456789
     *
     * @responseFile 422 responses/Registration/ResendOtp/422.json
     *
     * @responseFile 429 responses/429.json
     *
     * @responseFile 200 responses/Registration/ResendOtp/200.json
     *
     * @param  ResendOtpRequest  $request
     * @return json
     */
    public function resendRegistrationOtp(ResendOtpRequest $request)
    {
        $mobileNumber = (string)$request->mobileNumber;

        $checkDriver = $this->checkMobile($mobileNumber);
        if (!$checkDriver) {
            $error = ["msg" => trans('custom.userNotRegistered')];
            return response()->fail($error, 'E_UNAUTHORIZED');
        }

        $resend = $this->otp->reSendOtp($mobileNumber, self::OTP_TYPE);
        if ($resend['errorCode'] != 'E_OTP_SENT') {
            return response()->fail(['msg' => $resend['msg']], $resend['errorCode']);
        }

        return response()->success(['msg' => $resend['msg']], 'E_NO_ERRORS');
    }

}
