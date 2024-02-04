<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class BookingImage
 *
 * @property int $bookingImagesId
 * @property int $bookingDetailId
 * @property string $imageType
 * @property string $imageURL
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property BookingDetail $booking_detail
 *
 * @package App\Models
 */
class BookingImage extends Model
{
        protected $connection = 'reporting';
	protected $table = 'booking_images';
	protected $primaryKey = 'bookingImagesId';

	protected $casts = [
		'bookingDetailId' => 'int'
	];

	protected $fillable = [
		'bookingDetailId',
		'imageType',
		'imageURL'
	];

	public function booking_detail()
	{
		return $this->belongsTo(BookingDetail::class, 'bookingDetailId');
	}
}
