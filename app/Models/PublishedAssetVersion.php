<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Properties
 * @property string $id
 * @property string $published_asset_id
 * @property string $assetable_id
 * @property string $assetable_type
 * @property int $version
 * @property string $type
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 * Relations
 *
 * Attributes
 *
 */
class PublishedAssetVersion extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = [
        'published_asset_id',
        'assetable_id',
        'assetable_type',
        'version',
        'type',
    ];

    public function publishedAsset(): BelongsTo
    {
        return $this->belongsTo(PublishedAsset::class);
    }

    public function assetable(): MorphTo
    {
        return $this->morphTo();
    }
}
