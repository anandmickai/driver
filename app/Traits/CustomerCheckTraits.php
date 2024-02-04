<?php


namespace App\Traits;


use App\Models\DriverDetail;

trait CustomerCheckTraits
{
    /**
     * Check there is a user with mobile number
     *
     * @param $mobileNumber
     * @return mixed
     */
    public function checkMobile($mobileNumber)
    {
        $select = ['driverDetailId', 'driverStatus'];

        return DriverDetail::select($select)
            ->where('driverMobileNumber', $mobileNumber)
            ->orderBy('driverDetailId', 'DESC')->first();
    }
}
