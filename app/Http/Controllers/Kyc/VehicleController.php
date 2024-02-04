<?php

namespace App\Http\Controllers\Kyc;

use App\Http\Controllers\Controller;
use App\Models\BodyTypes;
use App\Models\VehicleType;
use App\Services\UploadImageService;
use App\Services\VehicleService;
use App\Http\Requests\General\AddVehicleDetailsRequest;

class VehicleController extends Controller
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
     * Add Vehicle
     * Driver will add his vehicle details
     *
     * @bodyParam driverVehicleNumber string required driver's vehicle number Example: AP26XA1234
     * @bodyParam vehicleTypeId integer required you will get this id from vehicleTypes api Example: 3
     * @bodyParam vehicleBrandDetails string required Whether vehicle is for temporary or permenant vehicle Example: TATA
     * @bodyParam vehicleColor string required vehicle color Example: red
     * @bodyParam vehicleModel string required vehicle model Example: Tiago
     * @bodyParam vehicleYear string required vehicle year Example: 2018
     * @bodyParam vehicleBodyTypeId string required vehicle body type Example: 1
     * @bodyParam vehicleMileage int required vehicle mileage Example: 13
     * @bodyParam vehicleDimensions string required vehicle Dimensions (height x width x depth)  Example: 1200mm x 1300mm x780mm
     * @bodyParam vehicleImages[] file required vehicle photo
     *
     * @authenticated
     *
     * @responseFile 422 responses/General/Vehicle/422.json
     *
     * @responseFile 400 responses/General/Vehicle/400.json
     *
     * @responseFile 200 responses/General/Vehicle/200.json
     *
     */

    public function addVehicleDetails(AddVehicleDetailsRequest $request)
    {
        $vehicleCheck = $this->vehicleService->vehicleCheck($request->driverId);
        if ($vehicleCheck > 0) {
            $error = ["msg" => trans('custom.vehicleAlreadyAdded')];
            return response()->fail($error, 'E_NOT_ACCEPTABLE');
        }

        $vehicleType = VehicleType::where('vehicleTypeid', $request->vehicleTypeId)->first();
        if (empty($vehicleType)) {
            $error = ["msg" => trans('custom.invalidVehicleType')];
            return response()->fail($error, 'E_REQUEST_INVALID');
        }

        $bodyTypes = BodyTypes::select('bodyTypeId', 'vehicleTypeId', 'bodyTypeName')
            ->where('vehicleTypeId', $request->vehicleTypeId)
            ->where('bodyTypeId', $request->vehicleBodyTypeId)
            ->where('isActive', 'Y')
            ->first();
        if (!$bodyTypes) {
            $error = ["msg" => trans('custom.invalidBodyType')];
            return response()->fail($error, 'E_REQUEST_INVALID');
        }

        $vehicleImages = $this->uploadDoc->uploadImage($request, 'vehicleImages');
        if ($vehicleImages['error']) {
            $response = ['error' => $vehicleImages['msg']];
            return response()->fail($response, $vehicleImages['errorCode']);
        }

        $vehicleDetailId = $this->vehicleService->addVehicleDetails($request, $vehicleType);
        $this->vehicleService->insertVehicleImages($vehicleDetailId, $vehicleImages['paths'], self::IMAGE_TYPES['PHOTO']);

        $response = ['msg' => trans('custom.addVehicleSuccess'), 'vehicleDetailId' => $vehicleDetailId];
        return response()->success($response, 'E_NO_ERRORS');
    }
}
