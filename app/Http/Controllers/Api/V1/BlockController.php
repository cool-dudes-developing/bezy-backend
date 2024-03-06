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
                    Block::whereNotIn('type', ['method', 'start', 'end'])
                        ->get()
                ),
                'assets' => PublishedAsset::all()
                    ->map(function ($asset) {
                        return [
                            'id' => $asset->id,
                            'name' => $asset->name,
                            'description' => $asset->description,
                            'block' => [
                                'id' => $asset->versions->first()->assetable_id,
                                'name' => $asset->name
                            ],
                        ];
                    })
            ]
        );
    }
}
