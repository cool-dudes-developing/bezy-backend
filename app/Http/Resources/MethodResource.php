<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Block */
class MethodResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'title' => $this->title,
            'description' => $this->description,
            'type' => $this->type,
            'project_id' => $this->projects->first()->id,
            'http_method' => $this->http_method,
            'uri' => $this->uri,
            'in' => $this->ports->where('direction', false)->count(),
            'out' => $this->ports->where('direction', true)->count(),
            'can_edit' => $this->projects()->whereHas(
                'members',
                fn($query) => $query->where('user_id', auth()->id())
                    ->where('role', '!=', 'viewer')
            )->exists(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'blocks' => MethodBlockResource::collection($this->whenLoaded('methodBlocks', fn() => $this->methodBlocks)),
            'connections' => ConnectionResource::collection($this->whenLoaded('connections', fn() => $this->connections)),
        ];
    }
}
