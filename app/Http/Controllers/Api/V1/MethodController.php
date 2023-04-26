<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\MethodRequest;
use App\Http\Resources\MethodResource;
use App\Models\Method;
use App\Models\Project;
use Illuminate\Http\Request;

class MethodController extends Controller
{
    public function index(Project $project)
    {
        return $this->respondWithSuccess('Methods retrieved', MethodResource::collection($project->methods));
    }

    public function store(MethodRequest $request, Project $project)
    {
        return $this->respondWithSuccess('Method created', MethodResource::make($project->methods()->create($request->validated())), 201);
    }

    public function show(Project $project, Method $method)
    {
        return $this->respondWithSuccess('Method retrieved', MethodResource::make($method));
    }

    public function update(MethodRequest $request, Project $project, Method $method)
    {
        $method->update($request->validated());

        return $this->respondWithSuccess('Method updated', MethodResource::make($method));
    }

    public function destroy(Project $project, Method $method)
    {
        $method->delete();

        return $this->respondWithSuccess('Method deleted');
    }
}
