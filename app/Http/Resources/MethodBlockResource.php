<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\MethodBlock */
class MethodBlockResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'x' => $this->x,
            'y' => $this->y,

            'method_id' => $this->parent_id,
            'block_id' => $this->block_id,
            'constant' => $this->when($this->block->type === 'variable', $this->constant),

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'project_id' => $this->block->projects()?->first()?->id,

            $this->merge(new BlockResource($this->block)),

            // ports dictionary
            'ports' => PortResource::collection($this->ports),

            // outPorts and inPorts are the same as ports, but
            'outPortIds' => $this->outPorts->pluck('id'),
            'inPortIds' => $this->inPorts->pluck('id'),

            'parent' => new MethodBlockResource($this->whenLoaded('parent')),
        ];
    }
}
