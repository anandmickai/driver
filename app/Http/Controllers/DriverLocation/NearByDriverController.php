<?php

namespace App\Http\Controllers\DriverLocation;

use App\Http\Controllers\Controller;
use App\Http\Requests\DriverLocation\GetLocationRequest;
use App\Services\DriverService;
use Illuminate\Support\Facades\Redis;

class NearByDriverController extends Controller {

    protected $driver;

    public function __construct(DriverService $driverService) {
        $this->driver = $driverService;
    }

    /**
     * @group Driver Geotagging
     * Driver Location 
     * Update the driver location
     *
     * @bodyParam long string required latitude  Example: 78.374174
     * @bodyParam lat string required longitude  Example: 78.374174
     *
     * @authenticated
     *
     */
    public function index(CreateRequest $request) {

        $KeyForLocation = config('needs.KeysForRedis.LocationOfDriver');
        $Mtype          = config('needs.KeysForRedis.LocationOfDriverMType');
        $Mvalue         = config('needs.KeysForRedis.LocationOfDriverMValue');
        $values         = Redis::command('GEORADIUS', [$KeyForLocation, $request->long, $request->lon, $Mvalue, $Mtype, 'WITHCOORD', 'WITHDIST']);
    }

}
