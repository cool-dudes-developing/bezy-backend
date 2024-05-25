<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Properties
 * @property string $id
 * @property string $name
 * @property string $description
 * @property int $downloads
 * @property string $author_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 * Relations
 * @property-read User $author
 * @property-read PublishedAssetVersion[] $versions
 * Attributes
 *
 */
class PublishedAsset extends Model
{
    use HasUuids, SoftDeletes;

    protected $casts = [
        'author_id' => 'string',
    ];

    protected $fillable = [
        'name',
        'description',
        'block_id',
        'author_id',
        'caption'
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function versions(): HasMany
    {
        return $this->hasMany(PublishedAssetVersion::class);
    }

    public function block(): BelongsTo
    {
        return $this->belongsTo(Block::class);
    }

    public function usersLiked(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_favorites');
    }

    public function getTagsAttribute()
    {
        return $this->block->methodBlocks->map(function ($methodBlock) {
            return $methodBlock->block->category;
        })->filter()->map(function ($category) {
            $split = explode('/', $category);
            return end($split);
        })->filter(function ($category) {
            return $category !== 'flow';
        })->unique()->values();
    }
}
