<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class DriverLoginHistory
 *
 * @property int $driverLoginHistoryId
 * @property int $driverDetailId
 * @property string $deviceType
 * @property string $isActive
 * @property string $deviceToken
 * @property Carbon $loginTime
 * @property Carbon $logoutTime
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property DriverDetail $driver_detail
 *
 * @package App\Models
 */
class DriverLoginHistory extends Model
{
	protected $table = 'driver_login_history';
	protected $primaryKey = 'driverLoginHistoryId';

	protected $casts = [
		'driverDetailId' => 'int'
	];

	protected $dates = [
		'loginTime',
		'logoutTime'
	];

	protected $fillable = [
		'driverDetailId',
		'deviceType',
		'isActive',
		'deviceToken',
		'loginTime',
		'logoutTime',
        'fcmToken'
	];

	public function driver_detail()
	{
		return $this->belongsTo(DriverDetail::class, 'driverDetailId');
	}
}
