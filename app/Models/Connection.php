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
 * @property string $block_id
 * @property string $from_method_block_id
 * @property string $to_method_block_id
 * @property string $from_port_id
 * @property string $to_port_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 * Relations
 * @property-read MethodBlock $source
 * @property-read MethodBlock $target
 * @property-read Port $sourcePort
 * @property-read Port $targetPort
 */
class Connection extends Model
{
    use SoftDeletes, HasFactory, HasUuids;

    protected $fillable = [
        'block_id',
        'from_method_block_id',
        'to_method_block_id',
        'from_port_id',
        'to_port_id',
    ];

    public function source(): BelongsTo
    {
        return $this->belongsTo(MethodBlock::class, 'from_method_block_id');
    }

    public function target(): BelongsTo
    {
        return $this->belongsTo(MethodBlock::class, 'to_method_block_id');
    }

    public function sourcePort(): BelongsTo
    {
        return $this->belongsTo(Port::class, 'from_port_id');
    }

    public function targetPort(): BelongsTo
    {
        return $this->belongsTo(Port::class, 'to_port_id');
    }
}
