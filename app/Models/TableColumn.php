<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Properties
 * @property string $id
 * @property string $table_id
 * @property string $relation_id
 * @property string $name
 * @property string $type
 * @property boolean $is_nullable
 * @property string $default
 * @property string $comment
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 * Relations
 *
 * Attributes
 *
 */
class TableColumn extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = [
        'table_id',
        'relation_id',
        'name',
        'type',
        'is_nullable',
        'default',
        'comment'
    ];

    public function table(): BelongsTo
    {
        return $this->belongsTo(ProjectTable::class, 'table_id');
    }
}
