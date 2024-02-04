<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class DocumentType
 * 
 * @property int $documentTypeID
 * @property string $documentTypeTitle
 * @property string $documentTypeDescription
 * @property Carbon $created_at
 * @property string $created_by
 * @property Carbon $updated_at
 * @property string $updated_by
 * 
 * @property Collection|DriverTypeDocument[] $driver_type_documents
 *
 * @package App\Models
 */
class DocumentType extends Model
{
	protected $table = 'document_type';
	protected $primaryKey = 'documentTypeID';

	protected $fillable = [
		'documentTypeTitle',
		'documentTypeDescription',
		'created_by',
		'updated_by'
	];

	public function driver_type_documents()
	{
		return $this->hasMany(DriverTypeDocument::class, 'documentTypeID');
	}
}
