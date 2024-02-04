<?php

namespace App\Http\Controllers\Wallet;

use App\Http\Controllers\Controller;
use App\Services\WalletService;
use Illuminate\Http\Request;

class BalanceController extends Controller
{
    public $wallet;

    public function __construct(WalletService $walletService)
    {
        $this->wallet = $walletService;
    }

    /**
     * @group Wallet
     * Wallet Balance
     * To get the balance of the wallet
     *
     * @authenticated
     *
     * @responseFile 200 responses/Wallet/balance-200.json
     *
     */
    public function index(Request $request)
    {
        $customer = \App\Models\DriverDetail::select('driverMobileNumber')->where('driverDetailId',
                                                                                      $request->driverId)->first();
        if (!$customer) {
            $error = ["msg" => trans('custom.noRecordsFound')];
            return response()->fail($error, 'E_NO_CONTENT');
        }

        $response = $this->wallet->getBalance($customer->driverMobileNumber);
        if ($response && $response->status !== 'success') {
            $error = ["msg" => trans('custom.somethingWentWrong')];
            return response()->fail($error, 'E_NO_CONTENT');
        }

        return response()->success($response->data, 'E_NO_ERRORS');
    }
}
