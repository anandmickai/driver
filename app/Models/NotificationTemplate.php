<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class NotificationTemplate
 * 
 * @property int $templateId
 * @property string $templateKey
 * @property string $templateType
 * @property string $templateBody
 * @property string $templateStatus
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @package App\Models
 */
class NotificationTemplate extends Model
{
	protected $table = 'notification_template';
	protected $primaryKey = 'templateId';

	protected $fillable = [
		'templateKey',
		'templateType',
		'templateBody',
		'templateStatus'
	];
}
