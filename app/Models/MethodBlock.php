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
 * @property string $method_id
 * @property string $blockable_id
 * @property string $blockable_type
 * @property int $x
 * @property int $y
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 * Relations
 * @property-read Method $method
 * @property-read Block|Method $block
 * @property-read ConnectedPort[] $connectedPorts
 */
class MethodBlock extends Model
{
    use SoftDeletes, HasFactory, HasUuids;

    protected $fillable = [
        'method_id',
        'blockable_id',
        'blockable_type',
        'x',
        'y',
    ];

    public function method(): BelongsTo
    {
        return $this->belongsTo(Method::class);
    }

    public function block()
    {
        return $this->morphTo('blockable');
    }

    public function connectedPorts(): HasMany
    {
        return $this->hasMany(ConnectedPort::class);
    }
}
