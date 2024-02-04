<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class BookingDetail
 * 
 * @property int $bookingDetailId
 * @property int $customerDetailId
 * @property int $driverDetailId
 * @property int $vehicleDetailId
 * @property string $bookingType
 * @property Carbon $bookingDate
 * @property string $bookingStatus
 * @property float $toalBillAmount
 * @property Carbon|null $orderAcceptedAt
 * @property Carbon|null $orderDeliveredAt
 * @property int $additionalHelper
 * @property int $insuranceStatus
 * @property string $fromlocation
 * @property string $toLocation
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property Collection|BookingCancellation[] $booking_cancellations
 * @property Collection|BookingImage[] $booking_images
 *
 * @package App\Models
 */
class BookingDetail extends Model
{       
        protected $connection = 'reporting';
	protected $table = 'booking_details';
	protected $primaryKey = 'bookingDetailId';

	protected $casts = [
		'customerDetailId' => 'int',
		'driverDetailId' => 'int',
		'vehicleDetailId' => 'int',
		'toalBillAmount' => 'float',
		'additionalHelper' => 'int',
		'insuranceStatus' => 'int'
	];

	protected $dates = [
		'bookingDate',
		'orderAcceptedAt',
		'orderDeliveredAt'
	];

	protected $fillable = [
		'customerDetailId',
		'driverDetailId',
		'vehicleDetailId',
		'bookingType',
		'bookingDate',
		'bookingStatus',
		'toalBillAmount',
		'orderAcceptedAt',
		'orderDeliveredAt',
		'additionalHelper',
		'insuranceStatus',
		'fromlocation',
		'toLocation'
	];

	public function booking_cancellations()
	{
		return $this->hasMany(BookingCancellation::class, 'bookingDetailId');
	}

	public function booking_images()
	{
		return $this->hasMany(BookingImage::class, 'bookingDetailId');
	}
}
