<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Project */
class ProjectResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'name' => $this->name,
            'description' => $this->description,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'role' => $this->userRole(auth()->id()),
            'can_edit' => in_array(
                $this->userRole(auth()->id()),
                ['owner', 'editor']
            ),
            'is_accepted' => $this->members->find(auth()->id())->pivot->accepted_at !== null,

            'user_id' => $this->user_id,

            'methods' => MethodResource::collection($this->whenLoaded('methods')),
            'members' => UserResource::collection($this->whenLoaded('members')),
        ];
    }
}
