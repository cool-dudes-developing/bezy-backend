<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Block;
use App\Models\PublishedAsset;

class MethodController extends Controller
{
    public function publish(Block $method)
    {
        $asset = PublishedAsset::create([
            'author_id' => auth()->id(),
            'name' => $method->title,
            'description' => $method->description,
            'downloads' => 0,
        ]);

        $asset->versions()->create([
            'assetable_type' => Block::class,
            'assetable_id' => $method->id,
            'version' => 1,
            'type' => 'release'
        ]);

        return $this->respondWithSuccess('Method published', $asset);
    }
}
