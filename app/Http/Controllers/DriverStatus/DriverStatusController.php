<?php

namespace App\Http\Controllers\DriverStatus;

use App\Http\Controllers\Controller;
use App\Http\Requests\DriverStatus\DriverStatusRequest;
use App\Services\DriverService;

class DriverStatusController extends Controller {

    protected $driver;

    public function __construct(DriverService $driverService) {
        $this->driver = $driverService;
    }

    /**
     * @group DriverRaidStatus
     * Driver Raid Status
     * Update the driver raid status with Geolocation
     *
     * @bodyParam raidStatus string required Driver Raid Status  Example: A
     * @bodyParam vehicleType string required Driver Vehicle Type  Example: A
     * @bodyParam long string required Driver Longitude  Example: 79.1455566
     * @bodyParam lat string required Driver latitude  Example: 14.1001101
     *
     * @authenticated
     *
     * @responseFile 422 responses/DriverRaidStatus/UpdateDriverRaidStatus/422.json
     *
     * @responseFile 200 responses/DriverRaidStatus/UpdateDriverRaidStatus/200.json
     *
     */
    public function index(DriverStatusRequest $request) {
        $driverStatus = $this->driver->getDriverRaidStatusWithCheck($request->driverId);
        if (!$driverStatus) {
            $error = ["msg" => trans('custom.noRecordsFound')];
            return response()->fail($error, 'E_NO_CONTENT');
        }
        $this->driver->updateDriverRaidStatus($request->driverId, $request->raidStatus);
//        Checking the driver status and updating the geolocation
        if ($request->input('raidStatus') == 'A') {
            \Redis::command('geoadd', [$request->vehicleType, $request->long, $request->lat, $request->driverId]);
        }
        else {
            \Redis::command('ZREM', [$request->vehicleType, $request->driverId]);
        }
        return response()->success(['msg' => 'Driver Raid status updated'], 'E_NO_ERRORS');
    }

}
