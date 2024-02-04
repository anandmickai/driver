<?php

namespace App\Http\Controllers\DriverLocation;

use App\Http\Controllers\Controller;
use App\Http\Requests\Locations\DriverLocationRequest;
use Illuminate\Support\Facades\Redis;

class DriverLocationController extends Controller {

    public function __construct() {
        
    }

    /**
     * @group Locations
     * Update Driver Location
     * Update the driver location using booking Id
     *
     * @bodyParam bookingId numeric required bookingId  Example: 1
     * @bodyParam long string required latitude  Example: 78.374174
     * @bodyParam lat string required longitude  Example: 78.374174
     *
     * @authenticated
     *
     */
    public function index(CreateRequest $request) {
        Redis::command('geoadd', [$request->bookingId, $request->long, $request->lon, $request->driverId]);
        return response()->success(['msg' => 'Success'], 'E_NO_ERRORS');
    }

}
