<?php

namespace App\Http\Controllers\Kyc;

use App\Http\Controllers\Controller;
use App\Http\Requests\Kyc\IdentityProofRequest;
use App\Models\DriverDetail;
use App\Models\DriverDocument;
use App\Models\DriverKycDetails;
use App\Services\UploadImageService;
use Illuminate\Http\Request;

class IdentityProofController extends Controller
{
    protected $uploadDoc;

    public function __construct(UploadImageService $uploadImageService) {
        $this->uploadDoc = $uploadImageService;
    }

    /**
     * @group Kyc
     * Upload Identity proof documents
     * This API is part of driver authentication. To validate his identity
     *
     * @bodyParam file file required file types mimes:jpeg,png,jpg,pdf are allowed as docs Example: driving-licence.jpeg
     * @bodyParam documentCategoryId numeric required Document category Id  Example: 1
     * @bodyParam referenceNumber string required Address Line 1  Example: ABAK3546GHJV
     *
     * @authenticated
     *
     * @responseFile 422 responses/Kyc/IdentityProof/422.json
     *
     * @responseFile 200 responses/Kyc/IdentityProof/200.json
     *
     */
    public function storeIdentity(IdentityProofRequest $request) {
        $upload = $this->uploadDoc->uploadImage($request);
        if ($upload['error']) {
            $response = ['error' => $upload['msg']];
            return response()->fail($response, $upload['errorCode']);
        }
        $driverDocumentID = $this->store($request);
        $this->insertAddressProofDetails($request, $driverDocumentID);
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
    private function store(Request $request) {
        $customerDocuments                     = new DriverDocument;
        $customerDocuments->driverDetailId     = $request->driverId;
        $customerDocuments->documentCategoryId = $request->documentCategoryId;
        $customerDocuments->save();

        return $customerDocuments->driverDocumentID;
    }

    /**
     * @param int $driverId
     */
    private function updateVerificationStatus($driverId)
    {
        $customerDetails = DriverDetail::find($driverId);
        $customerDetails->isVerified = config('needs.customerVerified.In-progress');
        $customerDetails->save();
        return;
    }

    /**
     * @param Request $request
     * @param int $driverDocumentID
     */
    private function insertAddressProofDetails($request, $driverDocumentID)
    {
        $addressProof = new DriverKycDetails;
        $addressProof->driverDocumentID = $driverDocumentID;
        $addressProof->referenceNumber = $request->referenceNumber;
        $addressProof->filePaths = $request->path;
        $addressProof->save();
        return;
    }
}
