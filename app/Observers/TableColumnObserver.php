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
                $tableColumn->table->project_id . '.' . $tableColumn->table->id,
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

    public function updated(TableColumn $tableColumn): void
    {
        // column updated in database
        \Schema::connection('mysql_projects')
            ->table(
                $tableColumn->table->project_id . '.' . $tableColumn->table->id,
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
    }

    public function deleted(TableColumn $tableColumn): void
    {
        // column deleted from database
        \Schema::connection('mysql_projects')
            ->table(
                $tableColumn->table->project_id . '.' . $tableColumn->table->id,
                function (Blueprint $table) use ($tableColumn) {
                    $table->dropColumn($tableColumn->name);
                }
            );
    }

    public function restored(TableColumn $tableColumn): void
    {
    }
}
