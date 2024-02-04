<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class VehicleType
 *
 * @property int $vehicleTypeId
 * @property string $vehicleTypeName
 * @property int $wheelerType
 * @property string $isVehicleActive
 * @property float $maxWeight
 * @property float $vehicleDimensions
 * @property Carbon $created_at
 * @property string $created_by
 * @property Carbon $updated_at
 * @property string $updated_by
 *
 * @property Collection|VehicleDetail[] $vehicle_details
 *
 * @package App\Models
 */
class VehicleType extends Model
{
    protected $connection = 'reporting';
	protected $table = 'vehicle_type';
	protected $primaryKey = 'vehicleTypeId';

	protected $casts = [
		'wheelerType' => 'int',
		'maxWeight' => 'float',
		'vehicleDimensions' => 'float'
	];

	protected $fillable = [
		'vehicleTypeName',
		'wheelerType',
		'isVehicleActive',
		'maxWeight',
		'vehicleDimensions',
        'vehicleImage',
		'created_by',
		'updated_by'
	];

	public function vehicle_details()
	{
		return $this->hasMany(VehicleDetail::class, 'vehicleTypeId');
	}
}
