<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\TableRelation */
class TableRelationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'source_table_id' => $this->source_table_id,
            'target_table_id' => $this->target_table_id,
            'source_column_id' => $this->source_column_id,
            'target_column_id' => $this->target_column_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
