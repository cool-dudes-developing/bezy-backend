<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property string $id
 * @property string $block_id
 * @property string $parent_id
 * @property int $x
 * @property int $y
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 * Relations
 * @property-read Block $block Block inside parent
 * @property-read Block $parent Parent block
 * @property-read Port[] $outPorts
 * @property-read Port[] $inPorts
 * @property-read Connection[] $connectionsIn
 * @property-read Connection[] $connectionsOut
 */
class MethodBlock extends Model
{
    use SoftDeletes, HasFactory, HasUuids;

    protected $fillable = [
        'block_id',
        'parent_id',
        'x',
        'y',
    ];

    public function block(): BelongsTo
    {
        return $this->belongsTo(Block::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Block::class, 'parent_id');
    }

    public function ports(): HasMany
    {
        // If this is the start block, then we want to get the ports that are
        // connected to this parent block (aka method). Otherwise, we want to
        // get the ports that are connected to the block itself.
        if ($this->block->name === 'start') {
            return $this->hasMany(Port::class, 'block_id', 'parent_id');
        } else {
            return $this->hasMany(Port::class, 'block_id', 'block_id');
        }
    }

    public function outPorts(): HasMany
    {
        if ($this->block->name === 'start') {
            // If this is the start block, then we return the out ports of the
            // block that is connected to this parent block (aka method).
            return $this->hasMany(Port::class, 'block_id', 'parent_id')->where('direction', 0);
        } else if ($this->block->name === 'end') {
            // If this is the end block, then it has no out ports.
            return $this->hasMany(Port::class, 'block_id', 'parent_id')->whereNull('id');
        } else {
            // Otherwise, we return the out ports of the block itself.
            return $this->hasMany(Port::class, 'block_id', 'block_id')->where('direction', 1);
        }
    }

    public function inPorts(): HasMany
    {
        if ($this->block->name === 'start') {
            // If this is the start block, then it has no in ports.
            return $this->hasMany(Port::class, 'block_id', 'parent_id')->whereNull('id');
        } else if ($this->block->name === 'end') {
            // If this is the end block, then we return the in ports of the
            // block that is connected to this parent block (aka method).
            return $this->hasMany(Port::class, 'block_id', 'parent_id')->where('direction', 1);
        } else {
            // Otherwise, we return the in ports of the block itself.
            return $this->hasMany(Port::class, 'block_id', 'block_id')->where('direction', 0);
        }
    }

    public function connectionsIn(): HasMany
    {
        return $this->hasMany(Connection::class, 'to_method_block_id');
    }

    public function connectionsOut(): HasMany
    {
        return $this->hasMany(Connection::class, 'from_method_block_id');
    }
}
