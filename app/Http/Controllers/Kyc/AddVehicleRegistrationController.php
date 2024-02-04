<?php

namespace App\Http\Controllers\Kyc;

use App\Http\Controllers\Controller;
use App\Http\Requests\Kyc\AddVehicleRegistrationRequest;
use App\Models\VehicleDetail;
use App\Services\UploadImageService;
use App\Services\VehicleService;
use Illuminate\Http\Request;

class AddVehicleRegistrationController extends Controller
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
     * Vehicle Registration
     * Driver will add his vehicle VIN Number
     *
     * @bodyParam vehicleVINNumber string required vehicle VIN Number Example: SFD345GHHJ
     * @bodyParam vehicleRegistration[] file required vehicle Registration Example: file.jpg
     *
     * @authenticated
     *
     * @responseFile 422 responses/General/Vehicle/Registration/422.json
     *
     * @responseFile 200 responses/General/Vehicle/Registration/200.json
     *
     */
    public function addVehicleRegistration(AddVehicleRegistrationRequest $request)
    {
        $vehicleCheck = $this->vehicleService->checkVehicle($request->vehicleDetailId, $request->driverId);
        if ($vehicleCheck <= 0) {
            $error = ["msg" => trans('custom.vehicleAlreadyAdded')];
            return response()->fail($error, 'E_NOT_ACCEPTABLE');
        }

        $vehicleImages = $this->uploadDoc->uploadImage($request, 'vehicleRegistration');
        if ($vehicleImages['error']) {
            $response = ['error' => $vehicleImages['msg']];
            return response()->fail($response, $vehicleImages['errorCode']);
        }

        $this->insertRegistrationDetails($request);
        $this->vehicleService->insertVehicleImages($request->vehicleDetailId, $vehicleImages['paths'],
            self::IMAGE_TYPES['REGISTRATION']);

        $response = ['msg' => trans('custom.addVehicleRegistrationSuccess')];
        return response()->success($response, 'E_NO_ERRORS');
    }

    private function insertRegistrationDetails($request)
    {
        $vehicleDetails = VehicleDetail::find($request->vehicleDetailId);
        $vehicleDetails->vehicleVINNumber = $request->vehicleVINNumber;
        $vehicleDetails->save();
        return $vehicleDetails->vehicleDetailId;
    }
}
