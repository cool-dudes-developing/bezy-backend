<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Port */
class PortResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'direction' => $this->direction ? 'out' : 'in',

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
