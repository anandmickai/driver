<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class DriverDocument
 *
 * @property int $driverDocumentID
 * @property int $driverDetailId
 * @property int $documentCategoryId
 * @property string $documentPath
 * @property string $isDeleted
 * @property string $documentStatus
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property DriverDetail $driver_detail
 * @property DocumentCategory $document_category
 *
 * @package App\Models
 */
class DriverDocument extends Model
{
	protected $table = 'driver_documents';
	protected $primaryKey = 'driverDocumentID';

	protected $casts = [
		'driverDetailId' => 'int',
		'documentCategoryId' => 'int'
	];

	protected $fillable = [
		'driverDetailId',
		'documentCategoryId',
		'isDeleted',
		'documentStatus'
	];

	public function driver_detail()
	{
		return $this->belongsTo(DriverDetail::class, 'driverDetailId');
	}

	public function document_category()
	{
		return $this->belongsTo(DocumentCategory::class, 'documentCategoryId');
	}

	public function driver_documents()
    {
        return $this->hasMany(DriverKycDetails::class, 'driverDocumentID');
    }
}
