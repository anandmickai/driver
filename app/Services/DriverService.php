<?php

namespace App\Services;

use App\Models\DriverDetail;
use App\Models\DriverDocument;
use App\Models\DriverKycDetails;
use App\Models\DriverTypeDocument;
use App\Models\DriverRaidStatus;

class DriverService {

    const REG_STEP_TWO = 2;

    /**
     * Get the Active customer details by mobile number
     *
     * @param  int  $mobileNumber
     * @return mixed
     */
    public function getActiveDriverByMobileNumber($mobileNumber) {
        $select = ['driverDetailId', 'driverFirstName', 'driverLastName', 'driverMiddleName',];

        return DriverDetail::select($select)
                        ->where('driverMobileNumber', $mobileNumber)
                        ->where('driverStatus', config('needs.userStatus.Active'))->firstOrFail();
    }

    /**
     * Updates the customer status based on his authenticity
     *
     * @param  int  $mobileNumber
     *
     * @return DriverDetail
     * @throws \Throwable
     */
    public function updateDriverStatus($mobileNumber) {
        return DriverDetail::where('driverMobileNumber', $mobileNumber)
                        ->update([
                            'driverStatus'     => config('needs.userStatus.Active'),
                            'registrationStep' => self::REG_STEP_TWO
        ]);
    }

    /**
     * get Customer Details By Mobile Number
     *
     * @param $mobileNumber
     * @return mixed
     */
    public function getCusDetailsByMobileNumber($mobileNumber) {
        $select = ['driverDetailId', 'driverStatus'];

        return DriverDetail::select($select)
                        ->where('driverMobileNumber', $mobileNumber)->first();
    }

    /**
     * @return array
     */
    public function getDriverTypeDocumentIds(): array {
        $driverTypeDocument  = DriverTypeDocument::select('documentCategoryId')
                ->groupBy('documentCategoryId')
                ->get();
        $driverTypeDocuments = [];
        if ($driverTypeDocument) {
            foreach ($driverTypeDocument as $docs) {
                $driverTypeDocuments[] = $docs->documentCategoryId;
            }
        }
        return $driverTypeDocuments;
    }

    public function getDriverDocuments($documentCalories, $driverId) {
        return DriverDocument::select('driverDocumentID', 'driverDetailId', 'documentCategoryId', 'documentStatus', 'isDeleted')
                        ->where('driverDetailId', $driverId)
                        ->whereIn('documentCategoryId', $documentCalories)
                        ->where('isDeleted', 'N')
                        ->where('documentStatus', '!=', 'D')
                        ->get();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getDriverTypeDocuments() {
        return DriverTypeDocument::with('document_category')->select('documentCategoryId')
                        ->groupBy('documentCategoryId')
                        ->get();
    }

    /**
     * Get the Driver raid status
     *
     * @param  int  $driverDetailId
     * @return mixed
     */
    public function getDriverRaidStatus($driverDetailId) {
        $select = ['raidStatus'];

        return DriverRaidStatus::select($select)->where('driverDetailId', $driverDetailId)
                        ->first();
    }

    /**
     * Get the Driver raid status with check
     *
     * @param  int  $driverDetailId
     * @return mixed
     */
    public function getDriverRaidStatusWithCheck($driverDetailId) {
        $select = ['raidStatus'];

        return DriverRaidStatus::select($select)->where('driverDetailId', $driverDetailId)
                        ->whereIn('raidStatus', config('needs.driverRaidStatusCheck'))
                        ->first();
    }

    /**
     * create the Driver raid status
     *
     * @param  int  $driverDetailId
     * @return mixed
     */
    public function createDriverRaidStatus($driverDetailId) {
        $driverStatus                 = DriverRaidStatus::updateOrCreate(['driverDetailId' => $driverDetailId]);
        $driverStatus->driverDetailId = $driverDetailId;
        $driverStatus->raidStatus     = config('needs.defaultDriverRaidStatus');
        $driverStatus->save();
        return $driverStatus;
    }

    /**
     * Update the Driver raid status
     *
     * @param  int  $driverDetailId
     * @param  string  $status
     * @return mixed
     */
    public function updateDriverRaidStatus($driverDetailId, $status) {

        return DriverRaidStatus::where('driverDetailId', $driverDetailId)
                        ->update(['raidStatus' => $status]);
    }

    /**
     * Update the Driver Profile
     *
     * @param  int  $driverDetailId
     * @param  string  $driverName
     * @param  string  $driverEmail
     * @param  string  $gender
     * @param  string  $dob
     * @return mixed
     */
    public function updateDriverProfile($request) {

        return DriverDetail::where('driverDetailId', $request->driverId)
            ->update([
                'driverFirstName' => $request->driverFirstName,
                'driverLastName' => $request->driverLastName,
                'driverMiddleName' => $request->driverMiddleName ?? null,
                'driverEmail' => $request->driverEmail,
                'gender' => $request->gender,
                'dateofBirth' => $request->dob,
                'socialInsuranceNumber' => $request->socialInsuranceNumber ?? '000-000-000',
                'legallyPermittedToWork' => $request->legallyPermittedToWork,
            ]);
    }

    /**
     * Get Driver Vehicle Details
     *
     * @param  int  $driverDetailId
     * @return mixed
     */
    public function getDriverVehicleDetails($driverDetailId, $vehicleColumns, $vehicleTypeColumns)
    {
        return \App\Models\VehicleDetail::select($vehicleColumns)->where('driverDetailId', $driverDetailId)
            ->whereIn('driverVehicleStatus', ['A', 'N'])
            ->with(
                [
                    'vehicle_type' => function ($query) use ($vehicleTypeColumns) {
                        $query->select($vehicleTypeColumns);
                    },
                    'body_types' => function ($query) {
                        $query->select('bodyTypeId', 'bodyTypeName');
                    },
                    'vehicle_images' => function ($query) {
                        $query->select('vehicleImageId', 'vehicleDetailId', 'imagePath', 'imageStatus', 'imageType')
                            ->where('imageStatus', '<>', 'A');
                    },
                ])
            ->first();
    }

    /**
     * @param $customerDocumentID
     * @return mixed
     */
    public function getDriverKycDetails($customerDocumentID)
    {
        return DriverKycDetails::where('driverDocumentID', $customerDocumentID)->first();
    }

    public function getVehicleImages($vehicleDetailId)
    {
        return \App\Models\VehicleImage::where('vehicleDetailId', $vehicleDetailId)
            ->where('imageStatus', '<>', 'A')
            ->get();
    }

}
