<?php

namespace App\Observers;

use App\Models\ProjectTable;
use Illuminate\Database\Eloquent\Model;

class ProjectTableObserver
{
    public function created(ProjectTable $projectTable): void
    {
//            `id` varchar(36) COLLATE utf8mb4_unicode_ci NOT NULL,
//            `created_at` timestamp NULL DEFAULT NULL,
//            `updated_at` timestamp NULL DEFAULT NULL,
//            `deleted_at` timestamp NULL DEFAULT NULL,
//            PRIMARY KEY (`id`)
        // Create the table in the project database
        \DB::connection('mysql_projects')->statement("CREATE DATABASE IF NOT EXISTS `{$projectTable->project_id}`");

        \Schema::connection('mysql_projects')->create($projectTable->project_id . '.' . $projectTable->id, function (\Illuminate\Database\Schema\Blueprint $table) {
            $table->uuid('id')->primary();
            $table->timestamps();
            $table->softDeletes();
        });

        $columns = $projectTable->columns()->makeMany([
            [
                'name' => 'id',
                'type' => 'uuid', // 36 characters for UUID
                'is_nullable' => false, // UUIDs are always required
                'default' => null, // No default value
                'comment' => "Default UUID column"
            ],
            [
                'name' => 'created_at',
                'type' => 'timestamp',
                'is_nullable' => true,
                'default' => null,
                'comment' => null
            ],
            [
                'name' => 'updated_at',
                'type' => 'timestamp',
                'is_nullable' => true,
                'default' => null,
                'comment' => null
            ],
            [
                'name' => 'deleted_at',
                'type' => 'timestamp',
                'is_nullable' => true,
                'default' => null,
                'comment' => null
            ]
        ]);

        $columns->each(function (Model $column) {
            $column->saveQuietly();
        });
    }

    public function updated(ProjectTable $projectTable): void
    {
    }

    public function deleted(ProjectTable $projectTable): void
    {
        $projectTable->columns()->delete();

        try {
            // Delete the table in the project database
            \DB::connection('mysql_projects')->statement("DROP TABLE IF EXISTS `{$projectTable->project_id}`.`{$projectTable->id}`");
            $projectTable->forceDelete();
            $projectTable->columns()->forceDelete();
        } catch (\Exception $e) {
            // Ignore
        }
    }

    public function restored(ProjectTable $projectTable): void
    {
    }
}
