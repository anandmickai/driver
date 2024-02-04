<?php


namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;


class DriverKycDetails extends Model
{
    protected $collection = 'driver_kyc_details';
    protected $connection = 'mongodb';

    protected $fillable = [
        'driverDocumentID',
        'addressLine1',
        'addressLine2',
        'city',
        'province',
        'postalCode',
        'filePaths',
        'referenceNumber'
    ];

    public function driver_documents()
    {
        return $this->belongsTo(DriverDocument::class, 'driverDocumentID');
    }
}
