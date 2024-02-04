<?php

namespace App\Http\Controllers\DriverStatus;

use App\Http\Controllers\Controller;
use App\Services\DriverService;
use Illuminate\Http\Request;
class GetDriverStatusController extends Controller {

    protected $driver;

    public function __construct(DriverService $driverService) {
        $this->driver = $driverService;
    }

    /**
     * @group GetDriverRaidStatus
     * Driver Raid Status
     * Get driver raid status
     *
     * @authenticated
     *
     * @responseFile 422 responses/DriverRaidStatus/GetDriverRaidStatus/422.json
     *
     * @responseFile 200 responses/DriverRaidStatus/GetDriverRaidStatus/200.json
     *
     */
    public function index(Request $request) {
        $driverStatus = $this->driver->getDriverRaidStatus($request->driverId);

        if (!$driverStatus) {
            $error = ["msg" => trans('custom.noRecordsFound')];
            return response()->fail($error, 'E_NO_CONTENT');
        }

        return response()->success($driverStatus, 'E_NO_ERRORS');
    }

}
