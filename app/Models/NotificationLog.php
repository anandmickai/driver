<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class NotificationLog
 *
 * @property int $notificationLogID
 * @property int $driverDetailId
 * @property string $notificationType
 * @property Carbon $sentDateTime
 * @property string $receipentAcknowledged
 * @property Carbon $receipentReceivedDate
 * @property string $receipentDeliveryReport
 * @property string $message
 * @property string $messageStatus
 * @property string $toAddress
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property DriverDetail $driver_detail
 *
 * @package App\Models
 */
class NotificationLog extends Model
{
	protected $table = 'notification_log';
	protected $primaryKey = 'notificationLogID';

	protected $casts = [
		'driverDetailId' => 'int'
	];

	protected $dates = [
		'sentDateTime',
		'receipentReceivedDate'
	];

	protected $fillable = [
		'driverDetailId',
		'notificationType',
		'sentDateTime',
		'receipentAcknowledged',
		'receipentReceivedDate',
		'receipentDeliveryReport',
		'message',
		'messageStatus',
		'toAddress'
	];

	public function driver_detail()
	{
		return $this->belongsTo(DriverDetail::class, 'driverDetailId');
	}
}
