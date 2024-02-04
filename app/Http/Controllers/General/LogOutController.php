<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use App\Services\DriverLoginHistoryService;
use Illuminate\Http\Request;

class LogOutController extends Controller
{
    protected $signOut;
    public function __construct(DriverLoginHistoryService $logOutService)
    {
        $this->signOut = $logOutService;
    }

    /**
     * @group General
     * Log out
     * Log the Driver out (Invalidate the token).
     *
     * @authenticated
     *
     * @return mixed
     */
    public function index()
    {
        $this->signOut->updateLogOutTime(auth()->user()->driverDetailId);
        auth()->logout();
        $response = ['msg' => trans('custom.loggedOut')];
        return response()->success($response, 'E_NO_ERRORS');
    }
}
