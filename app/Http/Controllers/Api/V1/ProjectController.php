<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;

class ProjectController extends Controller
{
    public function index()
    {
        return $this->respondWithSuccess(
            'Projects retrieved',
            ProjectResource::collection(
                auth()->user()->projects
            )
        );
    }

    public function store(ProjectRequest $request)
    {
        $validated = $request->validated();
        $project = Project::create([
                'description' => $validated['description'] ?? ''
            ] + $validated);
        auth()->user()->projects()->attach($project, [
            'role' => 'owner',
            'accepted_at' => now(),
        ]);
        return $this->respondWithSuccess(
            'Project created',
            ProjectResource::make(
                $project->load(['methods', 'members'])
            ),
            201
        );
    }

    public function show(Project $project)
    {
        return $this->respondWithSuccess(
            'Project retrieved',
            ProjectResource::make($project->load(['methods', 'members']))
        );
    }

    public function update(ProjectRequest $request, Project $project)
    {
        $project->update($request->validated());

        return $this->respondWithSuccess(
            'Project updated',
            ProjectResource::make($project)
        );
    }

    public function destroy(string $projectId)
    {
        $project = Project::withTrashed()->findOrFail($projectId);

        if ($project->trashed()) {
            $project->forceDelete();
            return $this->respondWithSuccess('Project permanently deleted');
        } else {
            $project->delete();

            return $this->respondWithSuccess('Project deleted');
        }
    }

    public function archived()
    {
        return $this->respondWithSuccess(
            'Archived projects retrieved',
            ProjectResource::collection(
                auth()->user()->projects()->onlyTrashed()->get()
            )
        );
    }

    public function restore(string $projectId)
    {
        $project = Project::onlyTrashed()->findOrFail($projectId);
        $project->restore();

        return $this->respondWithSuccess('Project restored');
    }
}
