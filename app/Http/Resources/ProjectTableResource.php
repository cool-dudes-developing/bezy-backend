<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\ProjectTable */
class ProjectTableResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'project_id' => $this->project_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'rows_count' => $this->table_rows,

            'columns' => TableColumnResource::collection($this->whenLoaded('columns')),
            'project' => new ProjectResource($this->whenLoaded('project')),
        ];
    }
}
