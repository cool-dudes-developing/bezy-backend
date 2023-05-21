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
            'project_id' => $this->whenPivotLoaded('project_blocks', fn() => $this->pivot->project_id),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'blocks' => MethodBlockResource::collection($this->whenLoaded('methodBlocks', fn() => $this->methodBlocks)),
            'connections' => ConnectionResource::collection($this->whenLoaded('connections', fn() => $this->connections)),
        ];
    }
}
