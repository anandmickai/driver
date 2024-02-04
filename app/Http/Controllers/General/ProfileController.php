<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use App\Models\DriverDetail;
use App\Models\Country;
use App\Services\DriverService;
use App\Services\GenerateExpirableDocLink;
use Illuminate\Http\Request;

class ProfileController extends Controller
{

    protected $driver;
    protected $presignedUrlGen;

    public function __construct(DriverService $driverService, GenerateExpirableDocLink $generateExpirableDocLink)
    {
        $this->driver = $driverService;
        $this->presignedUrlGen = $generateExpirableDocLink;
    }

    /**
     * @group General
     * Profile
     * Get the authenticated User.
     *
     * @authenticated
     *
     * @responseFile 200 responses/General/Me/200.json
     *
     * @param  Request  $request
     * @return json
     */
    public function me(Request $request)
    {
        $driverDocuments = DriverDetail::find($request->driverId);
        $documents = $this->driverDocumentsWithStatus($request);
        $countryDetails = Country::find($driverDocuments->countryCode);
        $response = $this->userInformation($driverDocuments, $request, $countryDetails);
        $response['documents'] = $documents;

        return response()->success($response, 'E_NO_ERRORS');
    }

    /**
     * Resource for filtering driver information and add few other information to return out.
     *
     * @param  DriverDetail  $driverDetail
     * @param  Request  $request
     * @return array
     */
    private function userInformation(DriverDetail $driverDetail, Request $request, $countryDetails): array
    {
        return [
            'driverDetailId' => $driverDetail->driverDetailId,
            'driverMobileNumber' => $driverDetail->driverMobileNumber,
            'driverEmail' => $driverDetail->driverEmail,
            'driverFirstName' => $driverDetail->driverFirstName,
            'driverLastName' => $driverDetail->driverLastName,
            'driverMiddleName' => $driverDetail->driverMiddleName,
            'gender' => $driverDetail->gender,
            'driverStatus' => $driverDetail->driverStatus,
            'socialInsuranceNumber' => $driverDetail->socialInsuranceNumber,
            'legallyPermittedToWork' => $driverDetail->legallyPermittedToWork,
            'isVerified' => $driverDetail->isVerified,
            'countryCode' => $driverDetail->countryCode,
            'documentVerified' => $driverDetail->documentVerified,
            'dateOfBirth' => $driverDetail->dateofBirth,
            'profileImagePath'  =>  $this->presignedUrlGen->generateLink($request, $driverDetail->profileImagePath),
            'countryName' => $countryDetails->countryName,
            'isdCode' => $countryDetails->isdCode,
            'currencyCode' => $countryDetails->currencyCode,
            'currencyName' => $countryDetails->currencyName,
            'currencySymbol' => $countryDetails->currencySymbol,
        ];
    }

    /**
     * Get driver Documents with their status
     *
     * @param int $driverId
     * @return array
     */
    private function driverDocumentsWithStatus(Request $request): array
    {
        $driverId = $request->driverId;
        $documentCategories = $this->driver->getDriverTypeDocumentIds();
        $driverTypeDocuments = $this->driver->getDriverTypeDocuments();

        $driverUploadedDocuments = $this->driver->getDriverDocuments($documentCategories, $driverId);

        $docs = [];
        foreach ($driverTypeDocuments as $document)
        {
            $docs[$document->documentCategoryId]= [
                'id' => $document->documentCategoryId,
                'name' => $document->document_category->categoryName,
            ];

            foreach ($driverUploadedDocuments as $key => $doc) {
                if ($document->documentCategoryId == $doc->documentCategoryId) {
                    $docs[$document->documentCategoryId]['driverDocumentID'] = $doc->driverDocumentID;
                    $docs[$document->documentCategoryId]['documentStatus'] = $doc->documentStatus;
                    $getCustomerKycDetails = $this->driver->getDriverKycDetails($doc->driverDocumentID);
                    if($getCustomerKycDetails) {
                        $imagePaths = [];
                        $i = 1;
                        foreach ($getCustomerKycDetails->filePaths as $paths) {
                            $imagePaths['img'.$i] = $this->presignedUrlGen->generateLink($request, $paths);
                            $i++;
                        }

                        $docs[$document->documentCategoryId]['documentPath'] = $imagePaths;
                        $getCustomerKycDetails->makeHidden([
                            '_id',
                            'driverDocumentID',
                            'filePaths',
                            'updated_at',
                            'created_at'
                        ]);
                        $docs[$document->documentCategoryId]['details'] = $getCustomerKycDetails;
                    } else {
                        $docs[$document->documentCategoryId]['documentPath'] = null;
                        $docs[$document->documentCategoryId]['details'] = null;
                    }
                }
            }

            if (!array_key_exists('driverDocumentID', $docs[$document->documentCategoryId])) {
                $docs[$document->documentCategoryId]['driverDocumentID'] = null;
                $docs[$document->documentCategoryId]['documentStatus'] = 'NU';
                $docs[$document->documentCategoryId]['documentPath'] = null;
                $docs[$document->documentCategoryId]['details'] = null;
            }
        }

        return array_values($docs);
    }

}
