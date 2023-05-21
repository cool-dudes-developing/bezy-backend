<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ConnectionRequest;
use App\Http\Resources\ConnectionResource;
use App\Models\Block;
use App\Models\Connection;
use App\Models\MethodBlock;
use App\Models\Port;
use App\Services\MethodBlockService;

class ConnectionController extends Controller
{
    public function __construct(
        private readonly MethodBlockService $methodBlockService
    )
    {
    }

    public function store(ConnectionRequest $request, Block $block)
    {
        $validated = $request->validated();
        return $this->respondWithSuccess('Connection created', ConnectionResource::make($this->methodBlockService->connect(
            MethodBlock::findOrFail($validated['from_method_block_id']),
            MethodBlock::findOrFail($validated['to_method_block_id']),
            Port::findOrFail($validated['from_port_id']),
            Port::findOrFail($validated['to_port_id']),
        )), 201);
    }

    public function destroy(Block $block, Connection $connection)
    {
        $connection->delete();
        return $this->respondWithSuccess('Connection deleted');
    }
}
