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
 * @property string $method_block_id
 * @property string $port_id
 * @property string $connected_to
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 * Relations
 * @property-read MethodBlock $methodBlock
 * @property-read Port $port
 * @property-read Port $connectedTo
 */
class ConnectedPort extends Model
{
    use SoftDeletes, HasFactory, HasUuids;

    protected $fillable = [
        'method_block_id',
        'port_id',
        'connected_to',
    ];

    public function methodBlock(): BelongsTo
    {
        return $this->belongsTo(MethodBlock::class);
    }

    public function port(): BelongsTo
    {
        return $this->belongsTo(Port::class);
    }

    public function connectedTo(): BelongsTo
    {
        return $this->belongsTo(Port::class, 'connected_to');
    }
}
