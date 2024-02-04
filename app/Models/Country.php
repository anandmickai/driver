<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Country
 *
 * @property int $countryId
 * @property string $countryName
 * @property string $countryCode
 * @property string $isdCode
 * @property string $countryFlag
 * @property string $currencyCode
 * @property string $currencyName
 * @property string $currencySymbol
 * @property Carbon $created_at
 * @property string $created_by
 * @property Carbon $updated_at
 * @property string $updated_by
 *
 * @property Collection|Commercial[] $commercials
 * @property Collection|CountryTax[] $country_taxes
 * @property Collection|State[] $states
 *
 * @package App\Models
 */
class Country extends Model
{
    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'reporting';
	protected $table = 'country';
	protected $primaryKey = 'countryId';

	protected $fillable = [
		'countryName',
		'countryCode',
		'isdCode',
		'countryFlag',
		'currencyCode',
		'currencyName',
		'currencySymbol',
		'created_by',
		'updated_by'
	];
}
