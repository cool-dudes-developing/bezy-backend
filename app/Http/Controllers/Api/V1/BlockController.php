<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\BlockResource;
use App\Models\Block;
use App\Models\PublishedAsset;
use Illuminate\Http\Request;

class BlockController extends Controller
{
    public function index()
    {
        return $this->respondWithSuccess('Blocks retrieved', BlockResource::collection(Block::all()));
    }

    public function store(Request $request)
    {
        return $this->respondWithError('Forbidden', 403);
    }

    public function show(Block $block)
    {
        return $this->respondWithSuccess('Block retrieved', new BlockResource($block));
    }

    public function update(Request $request, Block $block)
    {
        return $this->respondWithError('Forbidden', 403);
    }

    public function destroy(Block $block)
    {
        return $this->respondWithError('Forbidden', 403);
    }

    public function templates()
    {
        return $this->respondWithSuccess(
            'Block templates retrieved',
            [
                'blocks' => BlockResource::collection(
                    Block::whereNotIn('type', ['start', 'endpoint', 'method'])
                        ->orWhere(function ($query) {
                            $query->where('type', 'method')
                                ->whereHas('projects', function ($query) {
                                    $query->whereHas('members', function ($query) {
                                        $query->where('user_id', auth()->id());
                                    });
                                });
                        })
                        ->with('ports')
                        ->get()
                ),
                'assets' => PublishedAsset::all()
                    ->map(function ($asset) {
                        return [
                            'id' => $asset->id,
                            'name' => $asset->name,
                            'description' => $asset->description,
                            'is_liked' => $asset->usersLiked->find(auth()->id()) !== null,
                            'block' => [
                                'id' => $asset->block_id,
                                'name' => $asset->name
                            ],
                            'ports' => $asset->block->ports->map(function ($port) {
                                return [
                                    'id' => $port->id,
                                    'name' => $port->name,
                                    'type' => $port->type,
                                    'direction' => $port->direction,
                                    'default' => $port->default
                                ];
                            })
                        ];
                    })
            ]
        );
    }
}
