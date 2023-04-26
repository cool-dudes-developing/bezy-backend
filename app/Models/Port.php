<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property string $id
 * @property string $portable_id
 * @property string $portable_type
 * @property string $name
 * @property string $type
 * @property bool $direction
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 * Relations
 * @property-read Block $block
 */
class Port extends Model
{
    use SoftDeletes, HasFactory, HasUuids;

    protected $fillable = [
        'portable_id',
        'portable_type',
        'name',
        'type',
        'direction',
    ];

    public function block(): BelongsTo
    {
        return $this->belongsTo(Block::class);
    }
}
