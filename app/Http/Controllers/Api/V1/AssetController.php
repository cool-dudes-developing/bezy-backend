<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\AssetResource;
use App\Models\PublishedAsset;

class AssetController extends Controller
{
    public function index()
    {
        return $this->respondWithSuccess('Assets retrieved',
            AssetResource::collection(PublishedAsset::with(['author'])->get()->sortByDesc(function ($asset) {
                return $asset->usersLiked->count();
            }))
        );
    }

    public function liked()
    {
        return $this->respondWithSuccess('Assets retrieved',
            AssetResource::collection(PublishedAsset::with(['author', 'usersLiked'])->whereHas('usersLiked',
                function ($query) {
                    $query->where('user_id', auth()->id());
                }
            )->get())
        );
    }

    public function recent()
    {
        return $this->respondWithSuccess('Assets retrieved',
            AssetResource::collection(PublishedAsset::with(['author', 'usersLiked'])
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get())
        );
    }

    public function show(PublishedAsset $asset)
    {
        return $this->respondWithSuccess('Asset retrieved',
            AssetResource::make($asset->load(['author']))
        );
    }

    public function update(PublishedAsset $asset)
    {
        $asset->update(request()->all());
        return $this->respondWithSuccess('Asset updated', AssetResource::make($asset));
    }

    public function like(PublishedAsset $asset)
    {
        if ($asset->usersLiked()->find(auth()->id())) {
            $asset->usersLiked()->detach(auth()->id());
            return $this->respondWithSuccess('Asset unliked', AssetResource::make($asset));
        }
        $asset->usersLiked()->syncWithoutDetaching([auth()->id()]);
        return $this->respondWithSuccess('Asset liked', AssetResource::make($asset));
    }
}
