<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use App\Http\Requests\General\DriverDocumentRequest;
use App\Models\DriverDocument;
use App\Services\DriverService;
use App\Services\GenerateExpirableDocLink;

class DriverDocumentsController extends Controller {

    protected $presignedUrlGen;
    protected $driver;

    public function __construct(
        DriverService $driverService,
        GenerateExpirableDocLink $generateExpirableDocLink
    ) {
        $this->driver = $driverService;
        $this->presignedUrlGen = $generateExpirableDocLink;
    }

    /**
     * @group General
     * my documents
     * Get the document to preview
     *
     * @queryParam documentCategoryId numeric required Customer Document Category Id  Example: 1
     *
     * @authenticated
     *
     * @responseFile 422 responses/General/DriverDocs/422.json
     *
     * @responseFile 200 responses/General/DriverDocs/200.json
     *
     */
    public function index(DriverDocumentRequest $request) {
        $driverDocuments = DriverDocument::select('driverDocumentID')
                ->where('documentCategoryId', $request->documentCategoryId)
                ->where('driverDetailId', $request->driverId)
                ->where('documentStatus', '!=', 'D')
                ->where('isDeleted', '=', 'N')
                ->orderBy('driverDocumentID', 'desc')
                ->first();

        if (!$driverDocuments) {
            $error = ["msg" => trans('custom.noRecordsFound')];
            return response()->fail($error, 'E_NO_CONTENT');
        }

        $driverKycDetails = $this->driver->getDriverKycDetails($driverDocuments->driverDocumentID);
        if(!$driverKycDetails) {
            $error = ["msg" => trans('custom.noRecordsFound')];
            return response()->fail($error, 'E_NO_CONTENT');
        }

        $imagePaths = [];
        $i = 1;
        foreach ($driverKycDetails->filePaths as $paths) {
            $imagePaths['img'.$i] = $this->presignedUrlGen->generateLink($request, $paths);
            $i++;
        }
        $driverKycDetails->images = $imagePaths;
        $driverKycDetails->makeHidden([
            '_id',
            'driverDocumentID',
            'filePaths',
            'updated_at',
            'created_at'
        ]);
        return response()->success($driverKycDetails, 'E_NO_ERRORS');
    }

}
