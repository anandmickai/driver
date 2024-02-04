<?php

namespace App\Http\Controllers\PickUp;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pickup\uploadPickUpPictureRequest;
use App\Models\BookingImage;
use App\Services\UploadImageService;
use App\Services\BookingOtpService;
use Illuminate\Http\Request;

class UploadPictureController extends Controller {

    protected $uploadDoc;

    public function __construct(UploadImageService $uploadImageService, BookingOtpService $BookingService) {
        $this->uploadDoc = $uploadImageService;
        $this->booking   = $BookingService;
    }

    /**
     * @group Pickup
     * Upload Picture 
     * This API is used to upload picture of pickup items and send OTP to user
     *
     * @bodyParam file file required file types mimes:jpeg,png,jpg,pdf are allowed as docs Example: driving-licence.jpeg
     * @bodyParam bookingId numeric required Booking Id  Example: 1
     * @bodyParam confirmationType string required Confirmation Type  Example: Pickup,Drop
     * @authenticated
     *
     * @responseFile 422 responses/Pickup/UploadPicture/422.json
     *
     * @responseFile 200 responses/Pickup/UploadPicture/200.json
     *
     */
    public function uploadImage(uploadPickUpPictureRequest $request) {
        $booking = \App\Models\BookingDetail::find($request->bookingId);
        if (!$booking) {
            $error = ["msg" => trans('custom.noRecordsFound')];
            return response()->fail($error, 'E_NO_CONTENT');
        }
        $customer = \App\Models\CustomerDetail::find($booking->customerDetailId);
        if (!$customer) {
            $error = ["msg" => trans('custom.noRecordsFound')];
            return response()->fail($error, 'E_NO_CONTENT');
        }
        $upload = $this->uploadDoc->uploadImage($request);
        if ($upload['error']) {
            $response = ['error' => $upload['msg']];
            return response()->fail($response, $upload['errorCode']);
        }
        $this->booking->sendOtp($customer->customerMobileNumber);
        $this->store($request);
        $response = 'OTP successfully sent to customer registered mobile number.';
        return response()->success($response, 'E_NO_ERRORS');
    }

    /**
     * Insert Uploaded Pickup Images information
     *
     * @param  Request  $request
     * @return int
     */
    private function store($request) {
        $bookingImage                  = new BookingImage;
        $bookingImage->bookingDetailId = $request->bookingId;
        $bookingImage->imageType       = $request->confirmationType;
        $bookingImage->imageURL        = ($request->path) ?: null;
        $bookingImage->save();

        return $bookingImage->bookingImagesId;
    }

}
