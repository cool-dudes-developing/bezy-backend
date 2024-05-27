<?php

namespace App\Blocks;

use App\Models\CustomModel;
use App\Models\ProjectTable;

class DbreadBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['Key', 'Table'];

    public function run(): array
    {

        $table = ProjectTable::where('project_id', $this->service->getProject()->id)
            ->where('name', $this->Table)
            ->first();
        if (!$table)
            throw new \Exception('Table not found ' . $this->Table);
        $model = new CustomModel();
        $model->bind('mysql_projects', $table->project_id . '.' . $table->database_name);
        $model->fillable($table->columns->pluck('name')->toArray());

        return [
            'Result' => $model->find($this->Key)->toArray()
        ];
    }
}
