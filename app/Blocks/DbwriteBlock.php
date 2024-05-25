<?php

namespace App\Blocks;

use App\Models\CustomModel;
use App\Models\ProjectTable;

class DbwriteBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['Object', 'Table'];

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

        $row = collect(is_string($this->Object) ? json_decode($this->Object) : $this->Object);
        $row = $row->only($table->columns->pluck('name')->toArray());

        $instance = null;
        if ($row->get('id') === null) {
            $instance = $model->create($row->toArray());
        } else {
            $instance = $model->find($row->get('id'));
            if ($instance === null) {
                $instance = $model->create($row->toArray());
            } else {
                $instance->update($row->toArray());
            }
        }

        return $instance->fresh()->toArray();
    }
}
