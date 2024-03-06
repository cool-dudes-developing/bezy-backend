<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\PublishedAsset;

class AssetController extends Controller
{
    public function index()
    {
        return $this->respondWithSuccess('Assets retrieved', PublishedAsset::with(['versions', 'author'])->get());
    }

    public function show(PublishedAsset $asset)
    {
        return $this->respondWithSuccess('Asset retrieved', $asset->load(['versions', 'author']));
    }
}
