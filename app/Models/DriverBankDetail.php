<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class DriverBankDetail
 * 
 * @property int $driverBankDetailId
 * @property int $driverDetailId
 * @property int $bankNumber
 * @property int $transitNumber
 * @property int $accountNumber
 * @property string $bankName
 * @property string $accountName
 * @property string $driverBankStatus
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string|null $deleted_at
 * 
 * @property DriverDetail $driver_detail
 *
 * @package App\Models
 */
class DriverBankDetail extends Model
{
	use SoftDeletes;
	protected $table = 'driver_bank_details';
	protected $primaryKey = 'driverBankDetailId';

	protected $casts = [
		'driverDetailId' => 'int',
		'bankNumber' => 'int',
		'transitNumber' => 'int',
		'accountNumber' => 'int'
	];

	protected $fillable = [
		'driverDetailId',
		'bankNumber',
		'transitNumber',
		'accountNumber',
		'bankName',
		'accountName',
		'driverBankStatus'
	];

	public function driver_detail()
	{
		return $this->belongsTo(DriverDetail::class, 'driverDetailId');
	}
}
