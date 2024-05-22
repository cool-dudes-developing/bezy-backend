<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Properties
 * @property string $id
 * @property string $name
 * @property string $project_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 * Relations
 *
 * Attributes
 *
 */
class ProjectTable extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = [
        'name',
        'project_id'
    ];

    public function getDatabaseNameAttribute()
    {
        return str_replace('-', '_', $this->id);
    }

    public function columns(): HasMany
    {
        return $this->hasMany(TableColumn::class, 'table_id');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function relations(): HasMany
    {
        return $this->hasMany(TableRelation::class, 'source_table_id');
    }

    public function getTableRowsAttribute()
    {
        return \DB::connection('mysql_projects')->select("SELECT count(*) FROM `{$this->project_id}`.`{$this->database_name}`")[0]->{'count(*)'};
    }

}
