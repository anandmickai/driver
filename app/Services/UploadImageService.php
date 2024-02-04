<?php


namespace App\Services;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadImageService
{
    const DEFAULT_FILE_KEY = 'file';
    const DEFAULT_STORAGE_DRIVE = 's3';
    const DEFAULT_DIRECTORY = 'driver';

    /**
     * Common function for uploading Documents / images
     *
     * @param  Request  $request
     * @param  string  $fileKey
     * @param  string  $fileDirectory
     * @param  string  $storageDrive
     * @return array
     */
    public function uploadImage(
        Request $request,
        $fileKey = self::DEFAULT_FILE_KEY,
        $fileDirectory = self::DEFAULT_DIRECTORY,
        $storageDrive = self::DEFAULT_STORAGE_DRIVE
    ): array {
        if (!$request->driverId) {
            return ['error' => true, 'errorCode' => 'E_UNAUTHORIZED', 'paths' => [], 'msg' => trans('custom.userNotAllowed')];
        }

        $filePaths = [];
        if ($request->hasfile($fileKey)) {
            $files = $request->file($fileKey);

            if(is_array($files) && count($files) >= 1) {
                foreach($files as $file)
                {
                    $name = time().sha1($file->getClientOriginalName()).'.'.$file->extension();
                    $filePath = $fileDirectory.'/'.$request->driverId.'/'.$name;
                    Storage::disk($storageDrive)->put($filePath, file_get_contents($file));
                    $filePaths[] = $filePath;
                }
                $request->merge([
                    'path' => $filePaths
                ]);
            } else {
                $file = $request->file($fileKey);
                $name = time().sha1($file->getClientOriginalName()).'.'.$file->extension();
                $filePath = $fileDirectory.'/'.$request->driverId.'/'.$name;

                try {
                    Storage::disk($storageDrive)->put($filePath, file_get_contents($file));
                    $filePaths[] = $filePath;
                    $request->merge([
                        'path' => $filePaths
                    ]);
                } catch (\Exception $exception) {
                    return ['error' => true, 'errorCode' => 'E_SYSTEM', 'paths' => [], 'msg' => $exception->getMessage()];
                }
            }
        }
        return ['error' => false, 'errorCode' => 'E_NO_ERRORS', 'paths' => $filePaths, 'msg' => trans('custom.docUploadSuccess')];
    }

}
