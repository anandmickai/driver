<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class VehicleDetail
 *
 * @property int $vehicleDetailId
 * @property string $driverVehicleNumber
 * @property int $driverDetailId
 * @property string $driverVehicleStatus
 * @property Carbon $vehicleYear
 * @property string $vehicleBrandDetails
 * @property string $vehicleColor
 * @property string $vehicleModel
 * @property string $vehicleDimensions
 * @property string $vehicleBodyTypeId
 * @property int $vehicleMileage
 * @property string $vehicleVINNumber
 * @property string $vehicleInsurer
 * @property string $insurancePolicyNum
 * @property Carbon $insuranceExpiryDate
 * @property string $maxWeight
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property DriverDetail $driver_detail
 * @property VehicleType $vehicle_type
 * @property VehicleImage $vehicle_images
 *
 * @package App\Models
 */
class VehicleDetail extends Model
{
	protected $table = 'vehicle_details';
	protected $primaryKey = 'vehicleDetailId';

	protected $casts = [
		'driverDetailId' => 'int',
		'vehicleTypeId' => 'int',
	];

	protected $dates = [
        'insuranceExpiryDate',
	];

	protected $fillable = [
		'driverVehicleNumber',
		'driverDetailId',
		'driverVehicleStatus',
		'vehicleTypeId',
		'vehicleYear',
		'vehicleBrandDetails',
		'vehicleColor',
		'vehicleModel',
        'vehicleDimensions',
        'vehicleMileage',
        'vehicleMileageType',
        'vehicleVINNumber',
        'vehicleInsurer',
        'insurancePolicyNum',
        'insuranceExpiryDate',
		'maxWeight',
        'vehicleBodyTypeId'
	];

	public function driver_detail()
	{
		return $this->belongsTo(DriverDetail::class, 'driverDetailId');
	}

	public function vehicle_type()
	{
		return $this->belongsTo(VehicleType::class, 'vehicleTypeId');
	}

	public function vehicle_images()
    {
        return $this->hasMany(VehicleImage::class, 'vehicleDetailId');
    }

    public function body_types()
    {
        return $this->belongsTo(BodyTypes::class, 'vehicleBodyTypeId');
    }
}
