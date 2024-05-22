<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectBlockRequest;
use App\Http\Resources\BlockResource;
use App\Http\Resources\MethodResource;
use App\Http\Resources\ProjectBlockResource;
use App\Models\Block;
use App\Models\Method;
use App\Models\Project;
use App\Models\ProjectBlock;
use App\Services\BlockService;
use App\Services\MethodService;
use Illuminate\Http\Request;

/**
 * Manage project methods
 * methods are blocks that are part of a project
 * while blocks can exist on their own and be used in multiple projects
 * method blocks are unique to a project and can only be used in that project
 */
class ProjectMethodController extends Controller
{
    public function __construct(
        protected MethodService $methodService,
        protected BlockService  $blockService,
    )
    {
    }

    public function index(Project $project)
    {
        return $this->respondWithSuccess('Functions retrieved', MethodResource::collection($project->methods));
    }

    public function store(ProjectBlockRequest $request, Project $project)
    {
        // create new block
        $method = $this->blockService->save(
            $request->validated() + [
                'author_id' => auth()->id(),
            ],
            $project
        );

        // attach block to project
        // make block a method
        $project->methods()->attach($method->id);

        return $this->respondWithSuccess(
            'Function created',
            ProjectBlockResource::make(
                $method
            ),
            201
        );
    }

    public function show(Project $project, Block $block)
    {
        return $this->respondWithSuccess('Function retrieved', MethodResource::make($block->load(['methodBlocks.block', 'ports', 'connections'])));
    }

    public function update(ProjectBlockRequest $request, Project $project, Block $block)
    {
        $block->update($request->validated());

        return $this->respondWithSuccess('Function updated', MethodResource::make($block));
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
