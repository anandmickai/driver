<?php


namespace App\Services;


use App\Models\DriverLoginHistory;
use Illuminate\Http\Request;

class DriverLoginHistoryService
{
    const IS_ACTIVE_YES = 'Y';
    const IS_ACTIVE_NO = 'N';

    /**
     * INsert customer login information
     *
     * @param  Request  $request
     */
    public function insertLoginInfo(Request $request)
    {
        $deviceInfo = json_decode($request->header(config('needs.device-info-key')));
        $loginHistory = new DriverLoginHistory;
        $loginHistory->driverDetailId = $request->driverId;
        $loginHistory->deviceToken = $deviceInfo->deviceToken;
        $loginHistory->deviceType = $deviceInfo->deviceType;
        $loginHistory->fcmToken = $deviceInfo->fcmToken;
        $loginHistory->isActive = self::IS_ACTIVE_YES;
        $loginHistory->save();

        return;
    }

    /**
     * Update logOut time
     *
     * @param  int  $driverId
     */
    public function updateLogOutTime(int $driverId)
    {
        $loginHistory = DriverLoginHistory::where('driverDetailId', $driverId)
            ->where('isActive', self::IS_ACTIVE_YES)
            ->update([
                'logoutTime' => date('Y-m-d H:s:i'),
                'isActive' => self::IS_ACTIVE_NO
            ]);

        return;
    }

    /**
     * Get active sessions for the users
     * @param $driverId
     * @param $lastLogin
     * @return mixed
     */
    public static function getActiveSessions($driverId, $lastLogin)
    {
        return DriverLoginHistory::where('driverDetailId', $driverId)
            ->where('isActive', self::IS_ACTIVE_YES)
            ->whereRaw("`created_at` > STR_TO_DATE('$lastLogin', '%Y-%m-%d %H:%i:%s')")
            ->count();
    }

    /**
     * Get Current Driver Session
     * @param $driverId
     * @return mixed
     */
    public function getCurrentSession($driverId)
    {
        $columns = [
            'driverLoginHistoryId',
            'driverDetailId',
            'deviceType',
            'deviceToken',
            'fcmToken'
        ];
        return DriverLoginHistory::select($columns)->where('driverDetailId', $driverId)
            ->where('isActive', 'Y')->orderBy('driverLoginHistoryId', 'desc')->first();
    }

}
