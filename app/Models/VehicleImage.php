<?php


namespace App\Models;

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class VehicleImage
 *
 * @property int $vehicleImageId
 * @property int $vehicleDetailId
 * @property string $imagePath
 * @property string $imageStatus
 * @property string $imageType
 *
 * @property VehicleDetail $vehicle_details
 *
 * @package App\Models
 */
class VehicleImage extends Model
{
    protected $table = 'vehicle_images';
    protected $primaryKey = 'vehicleImageId';

    protected $casts = [
        'vehicleDetailId' => 'int'
    ];

    protected $fillable = [
        'vehicleDetailId',
        'imagePath',
        'imageStatus',
        'imageType',
    ];

    public function vehicle_details()
    {
        return $this->belongsTo(VehicleDetail::class, 'vehicleDetailId');
    }
}
