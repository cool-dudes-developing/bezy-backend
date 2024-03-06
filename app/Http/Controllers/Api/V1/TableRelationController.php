<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\TableRelationResource;
use App\Models\ProjectTable;
use App\Models\TableRelation;
use Illuminate\Http\Request;

class TableRelationController
{
    public function index(ProjectTable $table)
    {
        return TableRelationResource::collection($table->relations);
    }

    public function show(ProjectTable $table, TableRelation $relation)
    {
        return TableRelationResource::make($relation);
    }

    public function store(Request $request, ProjectTable $table)
    {
        $validated = $request->validate([
            'type' => ['required', 'in:' . implode(',', array_keys(config('table_builder.relation_types')))],
            'source_table_id' => ['required', 'exists:project_tables,id'],
            'target_table_id' => ['required', 'exists:project_tables,id'],
        ]);

        $relation = $table->relations()->create($validated);

        return TableRelationResource::make($relation);
    }
}
