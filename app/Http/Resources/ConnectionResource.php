<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Connection */
class ConnectionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'method_id' => $this->block_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'from_method_block_id' => $this->from_method_block_id,
            'to_method_block_id' => $this->to_method_block_id,
            'from_port_id' => $this->from_port_id,
            'to_port_id' => $this->to_port_id,

            'source' => new MethodBlockResource($this->whenLoaded('source')),
            'target' => new MethodBlockResource($this->whenLoaded('target')),
            'sourcePort' => new PortResource($this->whenLoaded('sourcePort')),
            'targetPort' => new PortResource($this->whenLoaded('targetPort')),
        ];
    }
}
