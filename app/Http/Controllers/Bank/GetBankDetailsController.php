<?php

namespace App\Http\Controllers\Bank;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GetBankDetailsController extends Controller {

    public function __construct() {

    }

    /**
     * @group Bank
     * Get Bank Details
     * Get the bank details of driver
     *
     * @authenticated
     *
     * @responseFile 422 responses/Bank/GetBankDetails/422.json
     *
     * @responseFile 200 responses/Bank/GetBankDetails/200.json
     */
    public function index(Request $request) {
        $select      = $this->getColumns();
        $bankDetails = \App\Models\DriverBankDetail::select($select)
                ->where('driverDetailId', $request->driverId)
                ->where('driverBankStatus', 'A')
                ->get();

        return response()->success($bankDetails, 'E_NO_ERRORS');
    }

    public function getColumns() {
        return [
            'driverBankDetailId',
            'driverDetailId',
            'bankNumber',
            'transitNumber',
            'accountNumber',
            'bankName',
            'accountName',
            'driverBankStatus'
        ];
    }

}
