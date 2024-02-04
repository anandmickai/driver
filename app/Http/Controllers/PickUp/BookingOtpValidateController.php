<?php

namespace App\Http\Controllers\PickUp;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pickup\BookingOtpRequest;
use App\Services\BookingOtpService;

class BookingOtpValidateController extends Controller {


    public function __construct(BookingOtpService $BookingService) {
        $this->booking = $BookingService;
    }

    /**
     * @group Pickup
     * OTP Validate 
     * This API is used to validate the pickup/drop OTP
     *
     * @bodyParam   mobileNumber numeric required mobile number of the customer Example: 9123456789
     * @bodyParam   otpNumber numeric required Otp shared on customer mobile number Example: 1234
     * @authenticated
     *
     * @responseFile 422 responses/Pickup/OtpValidate/422.json
     *
     * @responseFile 200 responses/Pickup/OtpValidate/200.json
     *
     */
    public function index(BookingOtpRequest $request) {
        $mobileNumber = (string) $request->mobileNumber;

        $otpValidate = $this->booking->validateOtp($request->otpNumber, $mobileNumber);
        if ($otpValidate) {
            $error = ["msg" => $otpValidate['msg']];
            return response()->fail($error, $otpValidate['errorCode']);
        }
        $response = ['msg' => 'OTP Validated Successfully'];
        return response()->success($response, 'E_NO_ERRORS');
    }

}
