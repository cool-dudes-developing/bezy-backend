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

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

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
