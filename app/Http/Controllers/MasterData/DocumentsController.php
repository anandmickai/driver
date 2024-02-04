<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\DriverTypeDocument;
use Illuminate\Http\Client\Request;

class DocumentsController extends Controller
{
    /**
     * @group Master Data
     * Documents required
     * Documents List used for authenticity. Customer must upload all these to unleash all features of application.
     * @queryParam customerType numeric required Type of the customer [1=> 'Corporate', 2=> 'Individual'] Example: 2
     *
     * @responseFile 422 responses/MasterData/Documents/422.json
     *
     * @responseFile 200 responses/MasterData/Documents/200.json
     * @param  Request  $request
     * @return mixed
     */
    public function index()
    {
        $documents = DriverTypeDocument::with('document_type', 'document_category')
            ->where('driverTypeDocumentStatus', 'A')->get();
        $response['documentCategory'] = $this->documentsByCategory($documents);
        return response()->success($response, 'E_NO_ERRORS');
    }

    private function documentsByCategory($customerTypeDocument)
    {
        if(! $customerTypeDocument){
            return false;
        }
        $docs = [];
        $documentTypes = [];
        foreach ($customerTypeDocument as $docKey => $document)
        {
            $documentTypes[$document->document_category->documentCategoryId][] =  [
                'id' => $document->document_type->documentTypeID,
                'name' => $document->document_type->documentTypeTitle,
                'description' => $document->document_type->documentTypeDescription
            ];

            $docs[$document->document_category->documentCategoryId] = [
                'id' => $document->document_category->documentCategoryId,
                'name' => $document->document_category->categoryName,
                'documentTypes' => $documentTypes[$document->document_category->documentCategoryId]
            ];
        }
        return array_values($docs);
    }
}
