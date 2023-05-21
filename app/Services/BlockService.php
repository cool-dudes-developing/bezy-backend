<?php

namespace App\Services;

use App\Models\Block;
use App\Models\Project;

class BlockService
{
    public function save(array $data): Block
    {
        $block = Block::create([
            'name' => $data['name'] ?? str_replace(' ', '_', strtolower($data['title'])) . '_' . uniqid(),
            'title' => $data['title'],
            'description' => $data['description'],
            'type' => $data['type'] ?? 'method',
        ]);

        if (isset($data['ports'])) {
            $block->ports()->createMany($data['ports']);
        }

        return $block;
    }
}
