<?php

namespace App\Services;

use App\Http\Requests\General\AddVehicleDetailsRequest;
use App\Models\VehicleDetail;
use App\Models\VehicleImage;
use App\Models\VehicleType;

class VehicleService
{
    /**
     * @param  AddVehicleDetailsRequest  $request
     * @param VehicleType $vehicleType
     * @return int
     */
    public function addVehicleDetails(AddVehicleDetailsRequest $request, VehicleType $vehicleType) : int
    {
        $vehicleDetail = new VehicleDetail;
        $vehicleDetail->driverVehicleNumber = $request->driverVehicleNumber;
        $vehicleDetail->driverDetailId = $request->driverId;
        $vehicleDetail->driverVehicleStatus = 'N';
        $vehicleDetail->vehicleTypeId = $request->vehicleTypeId;
        $vehicleDetail->vehicleYear = $request->vehicleYear;
        $vehicleDetail->vehicleBrandDetails = $request->vehicleBrandDetails;
        $vehicleDetail->vehicleColor = $request->vehicleColor;
        $vehicleDetail->vehicleModel = $request->vehicleModel;
        $vehicleDetail->vehicleDimensions = $vehicleType->vehicleDimensions;
        $vehicleDetail->vehicleBodyTypeId = $request->vehicleBodyTypeId;
        $vehicleDetail->vehicleMileage = $request->vehicleMileage;
        $vehicleDetail->vehicleMileageType = $request->vehicleMileageType;
        $vehicleDetail->maxWeight = $vehicleType->maxWeight;
        $vehicleDetail->save();

        return $vehicleDetail->vehicleDetailId;
    }

    /**
     * Check vehicle is added or not
     * @param $driverId
     * @return mixed
     */
    public function vehicleCheck($driverId)
    {
        return VehicleDetail::where('driverDetailId', $driverId)
            ->whereIn('driverVehicleStatus', ['A', 'N'])->count();
    }

    public function insertVehicleImages($vehicleDetailId, $imagePaths, $imageType)
    {
        foreach ($imagePaths as $imagePath) {
            $vehicleImage = new VehicleImage;
            $vehicleImage->vehicleDetailId = $vehicleDetailId;
            $vehicleImage->imagePath = $imagePath;
            $vehicleImage->imageStatus = 'U';
            $vehicleImage->imageType = $imageType;
            $vehicleImage->save();
        }
        return;
    }

    /**
     * Check vehicle is added or not
     * @param $vehicleDetailId
     * @param $driverId
     * @return mixed
     */
    public function checkVehicle($vehicleDetailId, $driverId)
    {
        return VehicleDetail::where('driverDetailId', $driverId)
            ->where('vehicleDetailId', $vehicleDetailId)
            ->whereIn('driverVehicleStatus', ['A', 'N'])->count();
    }
}
