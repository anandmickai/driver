<?php

namespace App\Http\Controllers\Wallet;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MyWalletController extends Controller
{
    /**
     * @group Wallet
     * My Wallet Info
     * To get the wallet details
     *
     * @authenticated
     *
     * @responseFile 200 responses/Wallet/my-wallet-200.json
     *
     */
    public function index()
    {
        $response = [
            'accountHolderId' => 1,
            'accountHolderMobile' => 9885109781,
            'accountHolderStatus' => 'A',
            'balance' => [
                'accountBalance' => 0.00,
                'accountSuspendBalance' => 0.00,
                'currentBalance' => 0.00
            ]
        ];
        return response()->success($response, 'E_NO_ERRORS');
    }
}
