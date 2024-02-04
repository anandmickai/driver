<?php

namespace App\Http\Controllers\Bank;

use App\Http\Controllers\Controller;
use App\Http\Requests\Bank\AddBankRequest;

class AddBankDetailsController extends Controller {

    public function __construct() {

    }

    /**
     * @group Bank
     * Add Bank Details
     * Add the bank details of driver
     *
     * @bodyParam bankNumber numeric required bookingId  Example: 9999
     * @bodyParam transitNumber numeric required bookingId  Example: 99999
     * @bodyParam accountNumber numeric required bookingId  Example: 9999999999999
     * @bodyParam bankName numeric required bookingId  Example: Induslnd
     * @bodyParam accountName numeric required bookingId  Example: John Clark
     *
     * @authenticated
     *
     * @responseFile 422 responses/Bank/AddBankDetails/422.json
     *
     * @responseFile 200 responses/Bank/AddBankDetails/200.json
     *
     */
    public function index(AddBankRequest $request) {
        $bankDetails                 = new \App\Models\DriverBankDetail;
        $bankDetails->driverDetailId = $request->driverId;
        $bankDetails->bankNumber     = $request->bankNumber;
        $bankDetails->transitNumber  = $request->transitNumber;
        $bankDetails->accountNumber  = $request->accountNumber;
        $bankDetails->bankName       = $request->bankName;
        $bankDetails->accountName    = $request->accountName;
        $bankDetails->save();
        return response()->success(['msg' => 'Successfully added bank details'], 'E_NO_ERRORS');
    }

}
