<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\MethodBlockRequest;
use App\Http\Resources\MethodBlockResource;
use App\Models\Block;
use App\Models\Method;
use App\Models\MethodBlock;
use App\Services\MethodBlockService;
use Illuminate\Http\Request;

class MethodBlockController extends Controller
{
    public function __construct(
        protected MethodBlockService $methodBlockService
    )
    {
    }

    public function index(Block $method)
    {
        return $this->respondWithSuccess('Method blocks retrieved', MethodBlockResource::collection($method->methodBlocks));
    }

    public function store(MethodBlockRequest $request, Block $method)
    {
        $validated = $request->validated();
        return $this->respondWithSuccess(
            'Method block created',
            new MethodBlockResource(
                $this->methodBlockService->save(
                    $method,
                    $validated
                )
            ),
            201);
    }

    public function show(Block $method, MethodBlock $block)
    {
        return $this->respondWithSuccess('Method block retrieved', new MethodBlockResource($methodBlock));
    }

    public function update(MethodBlockRequest $request, Block $method, MethodBlock $block)
    {
        return $this->respondWithSuccess('Method block updated', new MethodBlockResource(tap($block)->update($request->validated())));
    }

    public function destroy(Block $method, MethodBlock $block)
    {
        $block->delete();
        return $this->respondWithSuccess('Method block deleted');
    }
}
