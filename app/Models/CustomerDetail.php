<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * Class CustomerDetail
 *
 * @property int $customerDetailId
 * @property string $customerMobileNumber
 * @property int $customerType
 * @property string $customerEmail
 * @property string $customerName
 * @property string $gender
 * @property int $registrationStep
 * @property Carbon $activatedDate
 * @property string $customerStatus
 * @property string $isVerified
 * @property string $countryCode
 * @property string $documentVerified
 * @property Carbon $dateofBirth
 * @property string $profileImagePath
 * @property string $customerSecret
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 *
 * @package App\Models
 */
class CustomerDetail extends Authenticatable implements JWTSubject
{
    use Notifiable;

    protected $connection = 'customer';
	protected $table = 'customer_details';
	protected $primaryKey = 'customerDetailId';

	protected $casts = [
		'customerType' => 'int',
		'registrationStep' => 'int'
	];

	protected $dates = [
		'activatedDate',
		'dateofBirth'
	];

	protected $hidden = [
	    'customerSecret'
    ];

	protected $fillable = [
		'customerMobileNumber',
		'customerType',
		'customerEmail',
		'customerName',
		'gender',
		'registrationStep',
		'activatedDate',
		'customerStatus',
		'isVerified',
		'countryCode',
		'documentVerified',
		'dateofBirth',
		'profileImagePath',
		'customerSecret'
	];

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
}
