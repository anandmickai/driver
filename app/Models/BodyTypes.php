<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BodyTypes extends Model
{
    protected $connection = 'reporting';
    protected $table = 'body_types';
    protected $primaryKey = 'bodyTypeId';

    protected $fillable = [
        'vehicleTypeId',
        'bodyTypeName',
        'isActive',
        'created_by',
        'updated_by'
    ];

    public function vehicle_details()
    {
        return $this->hasMany(VehicleDetail::class, 'vehicleBodyTypeId');
    }
}
