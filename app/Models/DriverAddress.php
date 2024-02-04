<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class DriverAddress
 *
 * @property int $driverAddressId
 * @property int $driverDetailId
 * @property string $addressType
 * @property string $addressName
 * @property string $addressLine
 * @property string $gpsLocation
 * @property float $latitude
 * @property float $longitude
 * @property string $city
 * @property string $state
 * @property string $country
 * @property string $postalZipCode
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property DriverDetail $driver_detail
 *
 * @package App\Models
 */
class DriverAddress extends Model
{
	protected $table = 'driver_address';
	protected $primaryKey = 'driverAddressId';

	protected $casts = [
		'driverDetailId' => 'int',
		'latitude' => 'float',
		'longitude' => 'float'
	];

	protected $fillable = [
		'driverDetailId',
		'addressType',
		'addressName',
		'addressLine',
		'gpsLocation',
		'latitude',
		'longitude',
		'city',
		'state',
		'country',
		'postalZipCode'
	];

	public function driver_detail()
	{
		return $this->belongsTo(DriverDetail::class, 'driverDetailId');
	}
}
