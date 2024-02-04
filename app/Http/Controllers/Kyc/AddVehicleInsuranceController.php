<?php

namespace App\Http\Controllers\Kyc;

use App\Http\Controllers\Controller;
use App\Http\Requests\Kyc\AddVehicleInsuranceRequest;
use App\Http\Requests\Kyc\AddVehicleRegistrationRequest;
use App\Models\VehicleDetail;
use App\Services\UploadImageService;
use App\Services\VehicleService;
use Illuminate\Http\Request;

class AddVehicleInsuranceController extends Controller
{
    protected $vehicleService;
    protected $uploadDoc;

    const IMAGE_TYPES = [
        'PHOTO' => 'P',
        'INSURANCE' => 'I',
        'REGISTRATION' => 'R',
        'NONE' => 'N'
    ];

    public function __construct(
        VehicleService $vehicleService,
        UploadImageService $uploadImageService
    ) {
        $this->vehicleService = $vehicleService;
        $this->uploadDoc = $uploadImageService;
    }

    /**
     * @group Vehicle
     * Vehicle Insurance
     * Driver will add his vehicle Insurance Details
     *
     * @bodyParam vehicleDetailId int required vehicle Detail Id Example: 54
     * @bodyParam insurancePolicyNum string required insurance Policy Number Example: ADSFGF345DF
     * @bodyParam insuranceExpiryDate string required insurance Expiry Date Example: 2022-02-12
     * @bodyParam vehicleInsurer string required vehicle insurer Example: TATA AIG INSURANCE PVT. LTD.
     * @bodyParam insurancePolicy[] file required vehicle insurance Policy Example: file.JPG
     *
     * @authenticated
     *
     * @responseFile 422 responses/General/Vehicle/Registration/422.json
     *
     * @responseFile 200 responses/General/Vehicle/Registration/200.json
     *
     */
    public function addVehicleInsurance(AddVehicleInsuranceRequest $request)
    {
        $vehicleCheck = $this->vehicleService->checkVehicle($request->vehicleDetailId, $request->driverId);
        if ($vehicleCheck <= 0) {
            $error = ["msg" => trans('custom.vehicleAlreadyAdded')];
            return response()->fail($error, 'E_NOT_ACCEPTABLE');
        }

        $vehicleImages = $this->uploadDoc->uploadImage($request, 'insurancePolicy');
        if ($vehicleImages['error']) {
            $response = ['error' => $vehicleImages['msg']];
            return response()->fail($response, $vehicleImages['errorCode']);
        }

        $this->insertRegistrationDetails($request);
        $this->vehicleService->insertVehicleImages($request->vehicleDetailId, $vehicleImages['paths'],
            self::IMAGE_TYPES['INSURANCE']);

        $response = ['msg' => trans('custom.addVehicleInsuranceSuccess')];
        return response()->success($response, 'E_NO_ERRORS');
    }

    private function insertRegistrationDetails($request)
    {
        $vehicleDetails = VehicleDetail::find($request->vehicleDetailId);
        $vehicleDetails->insurancePolicyNum = $request->insurancePolicyNum;
        $vehicleDetails->insuranceExpiryDate = $request->insuranceExpiryDate;
        $vehicleDetails->vehicleInsurer = $request->vehicleInsurer;
        $vehicleDetails->save();
        return $vehicleDetails->vehicleDetailId;
    }
}
