<?php

namespace App\Observers;

use App\Models\TableColumn;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Builder;

class TableColumnObserver
{
    public function created(TableColumn $tableColumn): void
    {
        // column added to database
        \Schema::connection('mysql_projects')
            ->table(
                $tableColumn->table->project_id . '.' . $tableColumn->table->database_name,
                function (Blueprint $table) use ($tableColumn) {
                    $table->addColumn(
                        $tableColumn->type,
                        $tableColumn->name,
                        [
                            'nullable' => $tableColumn->is_nullable,
                            'default' => $tableColumn->default,
                            'comment' => $tableColumn->comment,
                            'length' => $tableColumn->length ?? Builder::$defaultStringLength
                        ]
                    );
                }
            );
    }

    public function updating(TableColumn $tableColumn): void
    {
        $conf = config('database.connections.mysql_projects');
        $conf['database'] = $tableColumn->table->project_id;
        config(['database.connections.temp' => $conf]);
        // column updated in database
        if ($tableColumn->isDirty('name')) {
            \Schema::connection('temp')
                ->table(
                    $tableColumn->table->database_name,
                    function (Blueprint $table) use ($tableColumn) {
                        $table->renameColumn($tableColumn->getOriginal('name'), $tableColumn->name);
                    }
                );
        }
        \Schema::connection('temp')
            ->table(
                $tableColumn->table->database_name,
                function (Blueprint $table) use ($tableColumn) {
                    $table->addColumn(
                        $tableColumn->type,
                        $tableColumn->name,
                        [
                            'nullable' => $tableColumn->is_nullable,
                            'default' => $tableColumn->default,
                            'comment' => $tableColumn->comment,
                            'length' => $tableColumn->length ?? Builder::$defaultStringLength
                        ]
                    )->change();
                }
            );
        \DB::purge('temp');
    }

    public function deleted(TableColumn $tableColumn): void
    {
        // column deleted from database
        \Schema::connection('mysql_projects')
            ->table(
                $tableColumn->table->project_id . '.' . $tableColumn->table->database_name,
                function (Blueprint $table) use ($tableColumn) {
                    $table->dropColumn($tableColumn->name);
                }
            );
    }

    public function restored(TableColumn $tableColumn): void
    {
    }
}
