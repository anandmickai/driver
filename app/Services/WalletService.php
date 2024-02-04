<?php

namespace App\Services;

use App\Traits\Wallet;

class WalletService
{
    use Wallet;

    public function getBalance($mobileNumber)
    {
        $postParams = [
            'mobileNumber' => $mobileNumber
        ];
        $endpoint = config('needs.walletDetails.apis.getBalance.endpoint');
        return $response = $this->postMethod($postParams, $endpoint);
    }

    public function registerWallet($postParams)
    {
        $endpoint = config('needs.walletDetails.apis.registerWallet.endpoint');
        return $response = $this->postMethod($postParams, $endpoint);
    }
}
