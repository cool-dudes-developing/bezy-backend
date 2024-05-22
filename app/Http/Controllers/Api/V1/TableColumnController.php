<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\TableColumnResource;
use App\Models\ProjectTable;
use App\Models\TableColumn;
use Illuminate\Http\Request;

class TableColumnController extends Controller
{
    public function index(ProjectTable $table)
    {
        return TableColumnResource::collection($table->columns);
    }

    public function store(Request $request, ProjectTable $table)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:64'],
            'type' => ['in:' . implode(',', array_keys(config('table_builder.column_types')))],
            'is_nullable' => ['boolean'],
            'default' => ['nullable'],
            'comment' => ['nullable', 'string', 'max:1024'],
        ]);

        $column = $table->columns()->create([
            'name' => $validated['name'],
            'type' => config('table_builder.column_types')[$validated['type']]['type'],
            'is_nullable' => $validated['is_nullable'] ?? false,
            'default' => $validated['default'] ?? null,
            'comment' => $validated['comment'] ?? null,
        ]);

        return TableColumnResource::make($column);
    }

    public function show(ProjectTable $table, TableColumn $column)
    {
        return TableColumnResource::make($column);
    }

    public function update(Request $request, ProjectTable $table, TableColumn $column)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:64'],
            'type' => ['in:' . implode(',', array_keys(config('table_builder.column_types')))],
            'is_nullable' => ['boolean'],
            'default' => ['nullable'],
            'comment' => ['nullable', 'string', 'max:1024'],
        ]);

        $column->update([
            'name' => $validated['name'],
            'type' => config('table_builder.column_types')[$validated['type']]['type'],
            'is_nullable' => $validated['is_nullable'] ?? false,
            'default' => $validated['default'] ?? null,
            'comment' => $validated['comment'] ?? null,
        ]);


        return TableColumnResource::make($column);
    }

    public function destroy(ProjectTable $table, TableColumn $column)
    {
        $column->delete();

        return response()->noContent();
    }
}
