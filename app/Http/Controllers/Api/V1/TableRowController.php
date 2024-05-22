<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\CustomModel;
use App\Models\ProjectTable;
use Illuminate\Support\Collection;

class TableRowController
{
    private function replaceKeys(Collection $data, Collection $columns)
    {
        $result = collect();

        foreach ($data as $rowKey => $row) {
            $newRow = collect();
            foreach ($columns as $column) {
                $newRow->put($column->id, $row->{$column->name});
            }
            $result->put($rowKey, $newRow);
        }

        return $result;
    }

    private function namesToIds(Collection $data, Collection $columns)
    {
        $result = collect();

        foreach ($data as $row) {
            $newRow = collect();
            foreach ($row as $key => $value) {
                $newRow->put($columns->firstWhere('name', $key)->id, $value);
            }
            $result->push($newRow);
        }
        return $result;
    }

    private function idsToNames(Collection $data, Collection $columns)
    {
        $result = collect();

        foreach ($data as $rowKey => $row) {
            $newRow = collect();
            foreach ($row as $key => $value) {
                $newRow->put($columns->firstWhere('id', $key)->name, $value);
            }
            $result->put($rowKey, $newRow);
        }

        return $result;
    }

    public function index(ProjectTable $table)
    {
        return response()->json([
            'data' => $this->replaceKeys(
                \DB::connection('mysql_projects')->table($table->project_id . '.' . $table->database_name)->whereNull('deleted_at')->limit(100)->get(),
                $table->columns
            )->toArray(),
        ]);
    }

    public function update(ProjectTable $table)
    {
        $validated = request()->validate([
            'added' => ['nullable', 'array'],
            'changed' => ['nullable', 'array'],
            'delete' => ['nullable', 'array'],
        ]);

        $columns = $table->columns;

//        $data = collect($validated['data'])->map(function ($row) use ($columns) {
//            $newRow = collect();
//            foreach ($row as $key => $value) {
//                $newRow->put($columns->firstWhere('id', $key)->name, $value);
//            }
//            return $newRow;
//        });
        $added = $this->idsToNames(collect($validated['added'] ?? []), $columns);
        $changed = $this->idsToNames(collect($validated['changed'] ?? []), $columns);
        $delete = $this->idsToNames(collect($validated['delete'] ?? []), $columns);

        $indexColumn = $columns->firstWhere('name', 'id');

        $model = new CustomModel();
        $model->bind('mysql_projects', $table->project_id . '.' . $table->database_name);
        $model->fillable($columns->pluck('name')->toArray());
//        dump($model->find('6fad70f5-fd48-4498-9e14-eac708578ada')->toArray());


        $rows = collect([]);
        \DB::connection('mysql_projects')->beginTransaction();
        try {
            $added->each(function ($row, $rowKey) use ($rows, $model) {
                $rows->put($rowKey, $model->create($row->toArray()));
            });

            $changed->each(function ($row, $rowKey) use ($rows, $model, $indexColumn, $table) {
                $instance = $model->find($rowKey);
                if ($instance === null) {
                    throw new \Exception('Row not found, id: ' . $row->get('id') . ' in table: ' . $table->database_name . ' in project: ' . $table->project_id . '.');
                } else {
                    $instance->update($row->toArray());
                }
                $rows->put($rowKey, $instance->fresh());
            });

            $delete->each(function ($row) use ($model) {
                $model->find($row->get('id'))?->delete();
            });
            \DB::connection('mysql_projects')->commit();
        } catch (\Exception $e) {
            \DB::connection('mysql_projects')->rollBack();
            return response()->json([
                'error' => $e->getMessage(),
            ], 400);
        }


        return response()->json([
            'data' => $this->replaceKeys(
                $rows,
                $columns
            )->toArray(),
        ]);
    }
}
