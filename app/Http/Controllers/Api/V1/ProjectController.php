<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Http\Request;

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
        return $this->respondWithSuccess(
            'Project created',
            ProjectResource::make(
                auth()->user()->projects()->create($request->validated())
            ),
            201
        );
    }

    public function show(Project $project)
    {
        return $this->respondWithSuccess(
            'Project retrieved',
            ProjectResource::make($project->load('blocks'))
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

    public function destroy(Project $project)
    {
        $project->delete();

        return $this->respondWithSuccess('Project deleted');
    }
}
