<?php

namespace App\Http\Controllers\Bank;

use App\Http\Controllers\Controller;
use App\Http\Requests\Bank\DeleteBankAccountRequest;
use App\Models\DriverBankDetail;
use Illuminate\Http\Request;

class DeleteBankAccountController extends Controller
{
    /**
     * @group Bank
     * Delete Bank Account
     * Delete saved Bank Account by account id
     *
     * @bodyParam driverBankDetailId numeric required Saved bank account Id  Example: 12
     *
     * @authenticated
     *
     * @responseFile 200 responses/Bank/Delete/200.json
     *
     */
    public function delete(DeleteBankAccountRequest $request)
    {
        DriverBankDetail::where(
            [
                'driverBankDetailId' => $request->driverBankDetailId,
                'driverDetailId' => $request->driverId
            ]
        )->update([ 'driverBankStatus' => 'D']);

        return response()->success(['msg' => 'Successfully deleted bank account details'], 'E_NO_ERRORS');
    }
}
