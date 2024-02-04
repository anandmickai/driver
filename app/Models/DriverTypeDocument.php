<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class DriverTypeDocument
 * 
 * @property int $driverTypeDocumentId
 * @property int $documentCategoryId
 * @property int $documentTypeID
 * @property string $driverTypeDocumentStatus
 * @property Carbon $created_at
 * @property string $created_by
 * @property Carbon $updated_at
 * @property string $updated_by
 * 
 * @property DocumentCategory $document_category
 * @property DocumentType $document_type
 *
 * @package App\Models
 */
class DriverTypeDocument extends Model
{
	protected $table = 'driver_type_documents';
	protected $primaryKey = 'driverTypeDocumentId';

	protected $casts = [
		'documentCategoryId' => 'int',
		'documentTypeID' => 'int'
	];

	protected $fillable = [
		'documentCategoryId',
		'documentTypeID',
		'driverTypeDocumentStatus',
		'created_by',
		'updated_by'
	];

	public function document_category()
	{
		return $this->belongsTo(DocumentCategory::class, 'documentCategoryId');
	}

	public function document_type()
	{
		return $this->belongsTo(DocumentType::class, 'documentTypeID');
	}
}
