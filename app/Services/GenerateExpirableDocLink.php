<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class GenerateExpirableDocLink
{
    const DEFAULT_STORAGE_DRIVE = 's3';

    public function generateLink(
        Request $request,
        $filepath,
        $storageDrive = self::DEFAULT_STORAGE_DRIVE
    )
    {
        if (!$request->driverId) {
            Log::info('app.requests', ['error' => true, 'errorCode' => 'E_UNAUTHORIZED', 'msg' => trans('custom.userNotAllowed')]);
            return null;
        }

        if(!$filepath)
        {
            Log::info('app.requests', ['error' => true, 'errorCode' => 'E_UNPROCESSABLE', 'msg' => trans('custom.docUrlInvalid')]);
            return null;
        }

        try {
            $storageClient = Storage::disk($storageDrive)->getDriver()->getAdapter()->getClient();
            $expiry = Carbon::now()->addMinutes(config('needs.docLinkExpiryTime'));
            $command = $storageClient->getCommand('GetObject', [
                'Bucket' => \Config::get('filesystems.disks.s3.bucket'),
                'Key'    => $filepath
            ]);

            $urlRequest = $storageClient->createPresignedRequest($command, $expiry);

            return (string) $urlRequest->getUri();

        } catch (\Exception $exception) {
            Log::info('app.requests', ['error' => true, 'errorCode' => 'E_SYSTEM', 'msg' => $exception->getMessage()]);
            return null;
        }

    }
}
