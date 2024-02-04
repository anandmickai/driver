<?php

namespace App\Http\Controllers\Kyc;

use App\Http\Controllers\Controller;
use App\Http\Requests\Kyc\AddressProofRequest;
use App\Models\CustomerDetail;
use App\Models\DriverDetail;
use App\Models\DriverDocument;
use App\Models\DriverKycDetails;
use App\Services\UploadImageService;
use Illuminate\Http\Request;

class AddressProofController extends Controller
{
    protected $uploadDoc;

    public function __construct(UploadImageService $uploadImageService) {
        $this->uploadDoc = $uploadImageService;
    }

    /**
     * @group Kyc
     * Upload Address proof documents
     * This API is part of driver authentication. To validate his identity
     *
     * @bodyParam file file required file types mimes:jpeg,png,jpg,pdf are allowed as docs Example: driving-licence.jpeg
     * @bodyParam documentCategoryId numeric required Document category Id  Example: 1
     * @bodyParam addressLine1 string required Address Line 1  Example: "2469 Bloor St W"
     * @bodyParam addressLine2 string optional Address Line 2  Example: "Toronto, ON M6S 1P7"
     * @bodyParam city string required City  Example: Oratorio
     * @bodyParam province string required province  Example: Oratorio
     * @bodyParam postalCode string required postal / zip Code  Example: 11234
     *
     * @authenticated
     *
     * @responseFile 422 responses/Kyc/AddressProof/422.json
     *
     * @responseFile 200 responses/Kyc/AddressProof/200.json
     *
     */
    public function storeAddress(AddressProofRequest $request) {
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
        $addressProof->addressLine1 = $request->addressLine1;
        $addressProof->addressLine2 = $request->addressLine2;
        $addressProof->city = $request->city;
        $addressProof->province = $request->province;
        $addressProof->postalCode = $request->postalCode;
        $addressProof->filePaths = $request->path;
        $addressProof->save();
        return;
    }

}
