<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
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
 * @property string $author_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 * Relations
 * @property-read Port[] $ports
 * @property-read MethodBlock[] $methodBlocks
 * @property-read Connection[] $connections
 * Attributes
 * @property-read bool $pure
 */
class Block extends Model
{
    use SoftDeletes, HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'title',
        'description',
        'type',
        'uri',
        'http_method',
        'author_id',
        'category',
    ];

    public function ports(): HasMany
    {
        return $this->hasMany(Port::class);
    }

    public function methodBlocks(): HasMany
    {
        return $this->hasMany(MethodBlock::class, 'parent_id');
    }

    public function connections(): HasMany
    {
        return $this->hasMany(Connection::class);
    }

    public function getPureAttribute(): bool
    {
        return collect(config('blocks'))->flatten(1)->flatMap(fn($group) => $group)->keys()->contains($this->name);
    }

    public function getDefaultConstantAttribute(): ?string
    {
        return str_contains($this->category, 'constant')
            ? match ($this->ports->first()?->type) {
                'number' => '0',
                default => 'value',
            }
            : null;
    }
}
