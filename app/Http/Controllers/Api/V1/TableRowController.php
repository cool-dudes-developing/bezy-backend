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

        foreach ($data as $row) {
            $newRow = collect();
            foreach ($columns as $column) {
                $newRow->put($column->id, $row->{$column->name});
            }
            $result->push($newRow);
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

        foreach ($data as $row) {
            $newRow = collect();
            foreach ($row as $key => $value) {
                $newRow->put($columns->firstWhere('id', $key)->name, $value);
            }
            $result->push($newRow);
        }

        return $result;
    }

    public function index(ProjectTable $table)
    {
        return response()->json([
            'data' => $this->replaceKeys(
                \DB::connection('mysql_projects')->table($table->project_id . '.' . $table->id)->whereNull('deleted_at')->limit(100)->get(),
                $table->columns
            )->toArray(),
        ]);
    }

    public function update(ProjectTable $table)
    {
        $validated = request()->validate([
            'data' => ['nullable', 'array'],
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
        $data = $this->idsToNames(collect($validated['data'] ?? []), $columns);
        $delete = $this->idsToNames(collect($validated['delete'] ?? []), $columns);

        $indexColumn = $columns->firstWhere('name', 'id');

        $model = new CustomModel();
        $model->bind('mysql_projects', $table->project_id . '.' . $table->id);
        $model->fillable($columns->pluck('name')->toArray());
//        dump($model->find('6fad70f5-fd48-4498-9e14-eac708578ada')->toArray());

        $delete->each(function ($row) use ($model) {
            $model->find($row->get('id'))?->delete();
        });

        $rows = collect([]);
        $data->each(function ($row) use ($rows, $model, $indexColumn, $table) {
            if ($row->get('id') === null) {
                $rows->push(
                    $model->create($row->toArray())
                );

            } else {
                $instance = $model->find($row->get('id'));
                if ($instance === null) {
                    $instance = $model->create($row->toArray());
                } else {
                    $instance->update($row->toArray());
                }
                $rows->push($instance->fresh());
            }
        });


        return response()->json([
            'data' => $this->replaceKeys(
                $rows,
                $columns
            )->toArray(),
        ]);
    }
}
