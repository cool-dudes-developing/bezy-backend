<?php

namespace App\Blocks;

use App\Models\CustomModel;
use App\Models\ProjectTable;

class DblistBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['Table'];

    public function run(): array
    {

        $table = ProjectTable::where('project_id', $this->service->getProject()->id)
            ->where('name', $this->Table)
            ->first();
        if (!$table)
            throw new \Exception('Table not found ' . $this->Table);

        return [
            'Result' => \DB::connection('mysql_projects')->table($table->project_id . '.' . $table->database_name)->whereNull('deleted_at')->get()->toArray()
        ];
    }
}
