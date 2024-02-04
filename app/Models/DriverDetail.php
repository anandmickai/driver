<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * Class DriverDetail
 *
 * @property int $driverDetailId
 * @property string $driverMobileNumber
 * @property string $driverEmail
 * @property string $driverFirstName
 * @property string $driverLastName
 * @property string $driverMiddleName
 * @property string $socialInsuranceNumber
 * @property boolean $legallyPermittedToWork
 * @property string $gender
 * @property int $registrationStep
 * @property Carbon $activatedDate
 * @property string $driverStatus
 * @property string $isVerified
 * @property string $countryCode
 * @property string $documentVerified
 * @property Carbon $dateofBirth
 * @property string $profileImagePath
 * @property string $driverSecret
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property Collection|DriverAddress[] $driver_addresses
 * @property Collection|DriverDocument[] $driver_documents
 * @property Collection|DriverLoginHistory[] $driver_login_histories
 * @property Collection|NotificationLog[] $notification_logs
 * @property Collection|VehicleDetail[] $vehicle_details
 *
 * @package App\Models
 */
class DriverDetail extends Authenticatable implements JWTSubject
{
	protected $table = 'driver_details';
	protected $primaryKey = 'driverDetailId';

	protected $casts = [
		'registrationStep' => 'int',
        'dateOfBirth' => 'date:Y-m-d',
	];

	protected $dates = [
		'activatedDate',
	];

	protected $fillable = [
		'driverMobileNumber',
		'driverEmail',
		'driverFirstName',
		'driverLastName',
		'driverMiddleName',
        'socialInsuranceNumber',
        'legallyPermittedToWork',
		'gender',
		'registrationStep',
		'activatedDate',
		'driverStatus',
		'isVerified',
		'countryCode',
		'documentVerified',
		'dateofBirth',
		'profileImagePath',
		'driverSecret'
	];

	public function driver_addresses()
	{
		return $this->hasMany(DriverAddress::class, 'driverDetailId');
	}

	public function driver_documents()
	{
		return $this->hasMany(DriverDocument::class, 'driverDetailId');
	}

	public function driver_login_histories()
	{
		return $this->hasMany(DriverLoginHistory::class, 'driverDetailId');
	}


	public function notification_logs()
	{
		return $this->hasMany(NotificationLog::class, 'driverDetailId');
	}

	public function vehicle_details()
	{
		return $this->hasMany(VehicleDetail::class, 'driverDetailId');
	}

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * Get the user's full name.
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return trim("{$this->driverFirstName} {$this->driverLastName} {$this->driverMiddleName}");
    }
}
