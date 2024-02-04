<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use App\Http\Requests\General\UploadDocumentsRequest;
use App\Models\DriverDetail;
use App\Models\DriverDocument;
use App\Services\UploadImageService;
use Illuminate\Http\Request;

class UploadDocumentsController extends Controller
{

    protected $uploadDoc;

    public function __construct(UploadImageService $uploadImageService)
    {
        $this->uploadDoc = $uploadImageService;
    }

    /**
     * @group General
     * Upload documents
     * This API is part of driver authentication. To validate his identity
     *
     * @bodyParam file file required file types mimes:jpeg,png,jpg,pdf are allowed as docs Example: driving-licence.jpeg
     * @bodyParam documentCategoryId numeric required Document category Id  Example: 1
     *
     * @authenticated
     *
     * @responseFile 422 responses/General/UploadDocument/422.json
     *
     * @responseFile 401 responses/General/UploadDocument/401.json
     *
     * @responseFile 200 responses/General/UploadDocument/200.json
     *
     */
    public function uploadDocument(UploadDocumentsRequest $request)
    {
        $upload = $this->uploadDoc->uploadImage($request);
        if($upload['error'])
        {
            $response = ['error' => $upload['msg']];
            return response()->fail($response, $upload['errorCode']);
        }

        $this->store($request);
        $this->updateVerificationStatus($request->driverId);

        $response = ['msg' => $upload['msg']];

        return response()->success($response, 'E_NO_ERRORS');
    }

    /**
     * Insert Uploaded document information
     *
     * @param  Request  $request
     * @return int
     */
    private function store(Request $request)
    {
        $driverDocument = new DriverDocument;
        $driverDocument->driverDetailId = $request->driverId;
        $driverDocument->documentCategoryId = $request->documentCategoryId;
        $driverDocument->documentPath = ($request->path) ?: null;
        $driverDocument->save();

        return $driverDocument->driverDocumentID;
    }

    /**
     * @param int $driverId
     */
    private function updateVerificationStatus($driverId)
    {
        $driverDetails = DriverDetail::find($driverId);
        $driverDetails->isVerified = config('needs.customerVerified.In-progress');
        $driverDetails->save();
        return;
    }

}
