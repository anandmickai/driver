<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TelescopeEntriesTag
 * 
 * @property int $id
 * @property string $entry_uuid
 * @property string $tag
 * 
 * @property TelescopeEntry $telescope_entry
 *
 * @package App\Models
 */
class TelescopeEntriesTag extends Model
{
	protected $table = 'telescope_entries_tags';
	public $timestamps = false;

	protected $fillable = [
		'entry_uuid',
		'tag'
	];

	public function telescope_entry()
	{
		return $this->belongsTo(TelescopeEntry::class, 'entry_uuid', 'uuid');
	}
}
