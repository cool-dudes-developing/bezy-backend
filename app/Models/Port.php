<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property string $id
 * @property string $block_id
 * @property string $name
 * @property string $type
 * @property bool $direction 0 = in, 1 = out
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
        'block_id',
        'name',
        'type',
        'default',
        'direction',
    ];

    protected $casts = [
        'default' => 'string',
    ];

    public function block(): BelongsTo
    {
        return $this->belongsTo(Block::class);
    }
}
