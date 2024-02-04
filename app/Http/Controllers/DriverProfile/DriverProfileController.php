<?php

namespace App\Http\Controllers\DriverProfile;

use App\Http\Controllers\Controller;
use App\Http\Requests\DriverProfile\DriverProfileRequest;
use App\Services\DriverService;

class DriverProfileController extends Controller {

    protected $driver;

    public function __construct(DriverService $driverService) {
        $this->driver = $driverService;
    }

    /**
     * @group Driver Profile Update
     * Driver Profile
     * Update the driver profile
     *
     * @bodyParam driverFirstName string required driver Name  Example: John
     * @bodyParam driverLastName string required driver Name  Example: John
     * @bodyParam driverMiddleName string required driver Name  Example: John
     * @bodyParam driverEmail email required driver Email  Example: John@example.com
     * @bodyParam gender string required driverName  Example: M
     * @bodyParam dob string required dob  Example: 1970-01-01
     * @bodyParam legallyPermittedToWork boolean required driver Name  Example: 1
     *
     * @authenticated
     *
     * @responseFile 422 responses/DriverProfile/UpdateDriverProfile/422.json
     *
     * @responseFile 200 responses/DriverProfile/UpdateDriverProfile/200.json
     *
     */
    public function index(DriverProfileRequest $request) {
        $driverStatus = $this->driver->getDriverRaidStatus($request->driverId);

        if (!$driverStatus) {
            $error = ["msg" => trans('custom.noRecordsFound')];
            return response()->fail($error, 'E_NO_CONTENT');
        }
        $this->driver->updateDriverProfile($request);
        return response()->success(['msg' => 'Driver profile updated'], 'E_NO_ERRORS');
    }

}
