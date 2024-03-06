<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\ProjectTableResource;
use App\Models\Project;
use App\Models\ProjectTable;
use App\Services\TableColumnService;
use Illuminate\Http\Request;

class ProjectTableController
{
    public function __construct(
        private readonly TableColumnService $tableColumnService
    )
    {
    }

    public function index(Project $project)
    {
        return ProjectTableResource::collection($project->tables);
    }

    public function show(Project $project, ProjectTable $table)
    {
        return ProjectTableResource::make($table->load('columns'));
    }

    public function store(Request $request, Project $project)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:64'],
        ]);

        $table = $project->tables()->create($validated);

        return ProjectTableResource::make($table);
    }

    public function update(Request $request, Project $project, ProjectTable $table)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:64'],
        ]);

        $table->update($validated);

        return ProjectTableResource::make($table);
    }

    public function destroy(Project $project, ProjectTable $table)
    {
        $table->delete();

        return response()->noContent();
    }
}
