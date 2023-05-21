<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectBlockRequest;
use App\Http\Resources\MethodResource;
use App\Http\Resources\ProjectBlockResource;
use App\Models\Block;
use App\Models\Method;
use App\Models\Project;
use App\Models\ProjectBlock;
use App\Services\BlockService;
use App\Services\MethodService;
use Illuminate\Http\Request;

class ProjectBlockController extends Controller
{
    public function __construct(
        protected MethodService $methodService,
        protected BlockService  $blockService,
    )
    {
    }

    public function index(Project $project)
    {
        return $this->respondWithSuccess('Functions retrieved', MethodResource::collection($project->blocks));
    }

    public function store(ProjectBlockRequest $request, Project $project)
    {
        return $this->respondWithSuccess('Function created', ProjectBlockResource::make($this->blockService->save($request->validated(), $project)), 201);
    }

    public function show(Project $project, Block $block)
    {
        return $this->respondWithSuccess('Function retrieved', MethodResource::make($block->load(['methodBlocks.block', 'ports', 'connections'])));
    }

    public function update(ProjectBlockRequest $request, Project $project, ProjectBlock $block)
    {
        $block->update($request->validated());

        return $this->respondWithSuccess('Function updated', ProjectBlockResource::make($block));
    }

    public function destroy(Project $project, Block $block)
    {
        $block->delete();

        return $this->respondWithSuccess('Function deleted');
    }

    public function execute(Request $request, Project $project, Block $block)
    {
        return $this->methodService->execute($block, $request->all());
    }

    public function debug(Request $request, Project $project, Block $block)
    {
        return $this->methodService->debug($block, $request->all());
    }
}
