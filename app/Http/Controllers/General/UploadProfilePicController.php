<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use App\Http\Requests\General\UploadProfilePicRequest;
use App\Models\DriverDetail;
use App\Services\UploadImageService;

class UploadProfilePicController extends Controller
{
    protected $uploadPic;

    public function __construct(UploadImageService $uploadImageService)
    {
        $this->uploadPic = $uploadImageService;
    }

    /**
     * @group General
     * Upload profile pic
     * This API is part of driver authentication. To validate his identity
     *
     * @bodyParam file file required file types mimes:jpeg,png,jpg are allowed as docs Example: driving-licence.jpeg
     *
     * @authenticated
     *
     * @responseFile 422 responses/General/UploadProfilePic/422.json
     *
     * @responseFile 401 responses/General/UploadDocument/401.json
     *
     * @responseFile 200 responses/General/UploadProfilePic/200.json
     *
     */
    public function uploadProfilePic(UploadProfilePicRequest $request)
    {
        $upload = $this->uploadPic->uploadImage($request);
        if($upload['error'])
        {
            $response = ['error' => $upload['msg']];
            return response()->fail($response, $upload['errorCode']);
        }

        $this->store($request);

        $response = ['msg' => trans('custom.profilePicUploadSuccess')];

        return response()->success($response, 'E_NO_ERRORS');
    }

    private function store($request)
    {
        $driverDocuments = DriverDetail::find($request->driverId);
        $driverDocuments->profileImagePath = ($request->path) ? $request->path[0] : null;
        $driverDocuments->save();

        return $driverDocuments->driverDetailId;
    }
}
