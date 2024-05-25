<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property string $id
 * @property string $name
 * @property string $description
 * @property string $slug
 * @property string $user_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 * Relations
 * @property-read User $user
 * @property-read Block[] $methods
 */
class Project extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $fillable = [
        'name',
        'description',
        'slug',
        'user_id'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function methods(): BelongsToMany
    {
        return $this->belongsToMany(Block::class, 'project_blocks', 'project_id', 'block_id')->withTimestamps()->using(ProjectBlock::class);
    }

    public function tables(): HasMany
    {
        return $this->hasMany(ProjectTable::class, 'project_id');
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'project_members', 'project_id', 'user_id')->withPivot(['role', 'accepted_at'])->withTimestamps();
    }

    public function userRole(string $id)
    {
        return $this->members->find($id)->pivot->role;
    }
}
