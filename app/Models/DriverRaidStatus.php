<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class DriverRaidStatus
 * 
 * @property int $driverRaidStatusId
 * @property int $driverDetailId
 * @property string $raidStatus
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property DriverDetail $driver_detail
 *
 * @package App\Models
 */
class DriverRaidStatus extends Model
{
	protected $table = 'driver_raid_status';
	protected $primaryKey = 'driverRaidStatusId';

	protected $casts = [
		'driverDetailId' => 'int'
	];

	protected $fillable = [
		'driverDetailId',
		'raidStatus'
	];

	public function driver_detail()
	{
		return $this->belongsTo(DriverDetail::class, 'driverDetailId');
	}
}
