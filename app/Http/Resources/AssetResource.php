<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\PublishedAsset */
class AssetResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'caption' => $this->caption,
            'is_liked' => $this->usersLiked->contains(auth()->id()),
            'usages' => $this->block->usedInMethodBlocks->count(),
            'likes' => $this->usersLiked->count(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'versions_count' => $this->versions_count,
            'tags' => $this->tags,

            'author_id' => $this->author_id,

            'author' => new UserResource($this->whenLoaded('author')),
        ];
    }
}
