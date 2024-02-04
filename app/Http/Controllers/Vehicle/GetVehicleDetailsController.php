<?php

namespace App\Http\Controllers\Vehicle;

use App\Http\Controllers\Controller;
use App\Services\DriverService;
use App\Services\GenerateExpirableDocLink;
use Illuminate\Http\Request;

class GetVehicleDetailsController extends Controller {

    protected $driver;
    protected $presignedUrlGen;

    public function __construct(
        DriverService $driverService,
        GenerateExpirableDocLink $generateExpirableDocLink
    ) {
        $this->driver = $driverService;
        $this->presignedUrlGen = $generateExpirableDocLink;
    }

    /**
     * @group Driver
     * Driver Vehicle
     * Get driver vehicle details
     *
     * @authenticated
     *
     * @responseFile 422 responses/Vehicle/GetVehicleDetails/422.json
     *
     * @responseFile 200 responses/Vehicle/GetVehicleDetails/200.json
     *
     */
    public function index(Request $request) {
        $vehicleColumns     = self::getVehicleColumns();
        $vehicleTypeColumns = self::getVehicleTypeColumns();
        $driverVehicle      = $this->driver->getDriverVehicleDetails($request->driverId, $vehicleColumns, $vehicleTypeColumns);
        if (!$driverVehicle) {
            $error = ["msg" => trans('custom.noRecordsFound')];
            return response()->fail($error, 'E_NO_CONTENT');
        }
        foreach($driverVehicle->vehicle_images as $vehicle_image){
            $vehicle_image->imagePath = $this->presignedUrlGen->generateLink($request, $vehicle_image->imagePath);
        }
        return response()->success($driverVehicle, 'E_NO_ERRORS');
    }

    /**
     * Get Driver Vehicle Columns
     * @return array
     */
    private function getVehicleColumns() {
        return [
            'vehicleDetailId',
            'driverVehicleNumber',
            'driverDetailId',
            'driverVehicleStatus',
            'vehicleTypeId',
            'vehicleYear',
            'vehicleBrandDetails',
            'vehicleColor',
            'vehicleModel',
            'vehicleDimensions',
            'vehicleBodyTypeId',
            'vehicleMileage',
            'vehicleMileageType',
            'vehicleVINNumber',
            'vehicleInsurer',
            'insurancePolicyNum',
            'insuranceExpiryDate',
            'maxWeight'
        ];
    }

    /**
     * Get Driver Vehicle Columns     *
     * @return array
     */
    private function getVehicleTypeColumns() {
        return [
            'vehicleTypeId',
            'vehicleTypeName',
            'wheelerType',
            'isVehicleActive',
            'maxWeight',
            'vehicleDimensions',
            'vehicleImage',
        ];
    }

}
