<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * ProjectBlock is a pivot table between projects and blocks
 * AKA methods *  are blocks that are part of a project
 * while blocks can exist on their own and be used in multiple projects
 * method blocks are unique to a project and can only be used in that project
 *
 * @property string $project_id
 * @property string $block_id
 * @property string $created_at
 * @property string $updated_at
 * Relations
 * @property-read Project $project
 * @property-read Block $block
 */
class ProjectBlock extends Pivot
{
    protected $fillable = [
        'project_id',
        'block_id',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function block(): BelongsTo
    {
        return $this->belongsTo(Block::class);
    }
}
