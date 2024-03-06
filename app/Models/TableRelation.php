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
 * @property string $type
 * @property string $source_table_id
 * @property string $target_table_id
 * @property string $pivot_table_id
 * @property string $source_column_id
 * @property string $target_column_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 * Relations
 *
 * Attributes
 *
 */
class TableRelation extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = [
        'type',
        'source_table_id',
        'target_table_id',
        'pivot_table_id',
        'source_column_id',
        'target_column_id'
    ];

    public function sourceTable(): BelongsTo
    {
        return $this->belongsTo(ProjectTable::class, 'source_table_id');
    }

    public function targetTable(): BelongsTo
    {
        return $this->belongsTo(ProjectTable::class, 'target_table_id');
    }

    public function sourceColumn(): BelongsTo
    {
        return $this->belongsTo(TableColumn::class, 'source_column_id');
    }

    public function targetColumn(): BelongsTo
    {
        return $this->belongsTo(TableColumn::class, 'target_column_id');
    }
}
