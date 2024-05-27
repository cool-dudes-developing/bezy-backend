<?php

namespace App\Blocks;

use App\Models\CustomModel;
use App\Models\ProjectTable;

class DbdeleteBlock extends GenericBlock implements BlockInterface
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

        $instance = $model->find($this->Key);
        if ($instance === null)
            throw new \Exception('Record not found ' . $this->Key);

        $instance->delete();

        return [];
    }
}
