<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property string $id
 * @property string $name
 * @property string $description
 * @property string $type
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 * Relations
 * @property-read Port[] $ports
 * @property-read MethodBlock[] $methodBlocks
 */
class Block extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'name',
        'description',
        'type',
    ];

    public function ports(): HasMany
    {
        return $this->hasMany(Port::class);
    }

    public function methodBlocks(): HasMany
    {
        return $this->hasMany(MethodBlock::class);
    }
}
