<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class DriverOtp
 *
 * @property int $driverOTPId
 * @property string $otpTypeCode
 * @property string $recipient
 * @property string $otp
 * @property string $otpStatus
 * @property int $attempts
 * @property int $resendCount
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @package App\Models
 */
class DriverOtp extends Model
{
	protected $table = 'driver_otp';
	protected $primaryKey = 'driverOTPId';

	protected $casts = [
		'attempts' => 'int',
		'resendCount' => 'int'
	];

	protected $fillable = [
		'otpTypeCode',
		'recipient',
		'otp',
		'otpStatus',
		'attempts',
		'resendCount'
	];
}
