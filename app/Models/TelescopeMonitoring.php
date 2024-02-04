<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TelescopeMonitoring
 * 
 * @property int $id
 * @property string $tag
 *
 * @package App\Models
 */
class TelescopeMonitoring extends Model
{
	protected $table = 'telescope_monitoring';
	public $timestamps = false;

	protected $fillable = [
		'tag'
	];
}
