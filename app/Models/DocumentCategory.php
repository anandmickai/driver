<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class DocumentCategory
 * 
 * @property int $documentCategoryId
 * @property string $categoryName
 * @property string $categoryStatus
 * @property Carbon $created_at
 * @property string $created_by
 * @property Carbon $updated_at
 * @property string $updated_by
 * 
 * @property Collection|DriverDocument[] $driver_documents
 * @property Collection|DriverTypeDocument[] $driver_type_documents
 *
 * @package App\Models
 */
class DocumentCategory extends Model
{
	protected $table = 'document_category';
	protected $primaryKey = 'documentCategoryId';

	protected $fillable = [
		'categoryName',
		'categoryStatus',
		'created_by',
		'updated_by'
	];

	public function driver_documents()
	{
		return $this->hasMany(DriverDocument::class, 'documentCategoryId');
	}

	public function driver_type_documents()
	{
		return $this->hasMany(DriverTypeDocument::class, 'documentCategoryId');
	}
}
