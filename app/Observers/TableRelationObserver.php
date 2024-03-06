<?php

namespace App\Observers;

use App\Models\ProjectTable;
use App\Models\TableRelation;
use Illuminate\Support\Facades\Schema;

class TableRelationObserver
{
    private function createRelation(TableRelation $tableRelation): void
    {
        switch ($tableRelation->type) {
            case 'one-to-one':
                $foreignColumn = $tableRelation->sourceTable->columns()->create([
                    'name' => $tableRelation->targetTable->id . '_id',
                    'type' => 'uuid',
                    'is_nullable' => false,
                    'default' => null,
                    'comment' => "Foreign key to {$tableRelation->targetTable->name} table"
                ]);

                $tableRelation->source_column_id = $foreignColumn->id;
                $tableRelation->target_column_id = $tableRelation->targetTable->columns()->firstWhere('name', 'id')->id;
                break;
            case 'one-to-many-nullable':
            case 'one-to-many':
                $foreignColumn = $tableRelation->targetTable->columns()->create([
                    'name' => $tableRelation->sourceTable->id . '_id',
                    'type' => 'uuid',
                    'is_nullable' => $tableRelation->type === 'one-to-many-nullable',
                    'default' => null,
                    'comment' => "Foreign key to {$tableRelation->sourceTable->name} table"
                ]);

                $tableRelation->source_column_id = $tableRelation->sourceTable->columns()->firstWhere('name', 'id')->id;
                $tableRelation->target_column_id = $foreignColumn->id;
                break;
            case 'many-to-many':
                /** @var ProjectTable $pivotTable */
                $pivotTable = $tableRelation->sourceTable->project->tables()->createQuietly([
                    'name' => "Pivot table for {$tableRelation->sourceTable->name} and {$tableRelation->targetTable->name} tables",
                ]);

                Schema::connection('mysql_projects')
                    ->create(
                        $tableRelation->sourceTable->project_id . '.' . $pivotTable->id,
                        function (\Illuminate\Database\Schema\Blueprint $table) use ($tableRelation) {
                            $table->foreignUuid($tableRelation->sourceTable->id . '_id')
                                ->references('id', $tableRelation->id . '_mtms_fk')
                                ->on($tableRelation->sourceTable->project_id . '.' . $tableRelation->sourceTable->id)
                                ->cascadeOnDelete();
                            $table->foreignUuid($tableRelation->targetTable->id . '_id')
                                ->references('id', $tableRelation->id . '_mtmt_fk')
                                ->on($tableRelation->targetTable->project_id . '.' . $tableRelation->targetTable->id)
                                ->cascadeOnDelete();

                            $table->primary([$tableRelation->sourceTable->id . '_id', $tableRelation->targetTable->id . '_id']);
                        });

                $pivotTable->columns()->createManyQuietly([
                    [
                        'name' => $tableRelation->sourceTable->id . '_id',
                        'type' => 'uuid',
                        'is_nullable' => false,
                        'default' => null,
                        'comment' => "Foreign key to {$tableRelation->sourceTable->name} table"
                    ],
                    [
                        'name' => $tableRelation->targetTable->id . '_id',
                        'type' => 'uuid',
                        'is_nullable' => false,
                        'default' => null,
                        'comment' => "Foreign key to {$tableRelation->targetTable->name} table"
                    ]
                ]);

                $tableRelation->pivot_table_id = $pivotTable->id;
                $tableRelation->source_column_id = $tableRelation->sourceTable->columns()->firstWhere('name', 'id')->id;
                $tableRelation->target_column_id = $tableRelation->targetTable->columns()->firstWhere('name', 'id')->id;
        }
    }

    private function createRelationForeignKeys(TableRelation $tableRelation): void
    {
        switch ($tableRelation->type) {
            case 'one-to-one':
                Schema::connection('mysql_projects')->table(
                    $tableRelation->sourceTable->project_id . '.' . $tableRelation->sourceTable->id,
                    function (\Illuminate\Database\Schema\Blueprint $table) use ($tableRelation) {
                        $table->foreign($tableRelation->targetTable->id . '_id', $tableRelation->id . '_oto_fk')
                            ->references('id')
                            ->on($tableRelation->targetTable->project_id . '.' . $tableRelation->targetTable->id)
                            ->cascadeOnDelete();
                    }
                );
                break;
            case 'one-to-many-nullable':
            case 'one-to-many':
                Schema::connection('mysql_projects')->table(
                    $tableRelation->targetTable->project_id . '.' . $tableRelation->targetTable->id,
                    function (\Illuminate\Database\Schema\Blueprint $table) use ($tableRelation) {
                        $table->foreign($tableRelation->sourceTable->id . '_id', $tableRelation->id . '_otm_fk')
                            ->references('id')
                            ->on($tableRelation->sourceTable->project_id . '.' . $tableRelation->sourceTable->id)
                            ->cascadeOnDelete();
                    }
                );
                break;
            case 'many-to-many':
                break;
        }
    }

    public function creating(TableRelation $tableRelation): void
    {
        $this->createRelation($tableRelation);
    }

    public function created(TableRelation $tableRelation): void
    {
        $this->createRelationForeignKeys($tableRelation);
    }

    public function updating(TableRelation $tableRelation): void
    {
    }

    public function deleted(TableRelation $tableRelation): void
    {
    }

    public function restored(TableRelation $tableRelation): void
    {
    }
}
