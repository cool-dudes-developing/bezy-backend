<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\User */
class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,

            'projects' => ProjectResource::collection($this->whenLoaded('projects')),
            'role' => $this->whenPivotLoaded('project_members', function () {
                return $this->pivot->role;
            }),
            'accepted_at' => $this->whenPivotLoaded('project_members', function () {
                return $this->pivot->accepted_at;
            }),
        ];
    }
}
