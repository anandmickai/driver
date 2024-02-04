<?php

namespace App\Http\Middleware;

use App\Services\DeviceService;
use Closure;

class DeviceCheck
{
    protected $deviceCheck;
    public function __construct(DeviceService $deviceService)
    {
        $this->deviceCheck = $deviceService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $deviceCheck = $this->deviceCheck->validateDeviceInfo($request);
        if($deviceCheck)
        {
            return response()->fail($deviceCheck, 'E_PRECONDITION');
        }

        return $next($request);
    }
}
