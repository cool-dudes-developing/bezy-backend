<?php

namespace App\Services;

use App\Models\Block;
use Illuminate\Support\Facades\Log;

class BlockService
{
    public function getBlock(string $name): Block
    {
        if ($block = Block::firstWhere('name', $name)) {
            return $block;
        }
        Log::info('Block not exist in database yet, creating from config');
        $config = config('blocks.' . $name);
        if (!$config) {
            Log::error('Tried to create non generic block');
            throw new \Exception('Tried to create non generic block');
        }
        return $this->save($config);
    }

    public function save(array $data): Block
    {
        $block = Block::create([
            'name' => $data['name'] ?? str_replace(' ', '_', strtolower($data['title'])) . '_' . uniqid(),
            'title' => $data['title'] ?? $data['name'],
            'description' => $data['description'],
            'type' => $data['type'] ?? 'method',
            'author_id' => $data['author_id'] ?? null,
            'category' => $data['category'] ?? null,
        ]);

        if (isset($data['ports'])) {
            $block->ports()->createMany($data['ports']);
        } else {
            if ($block->type === 'endpoint') {
                $block->ports()->create([
                    'name' => 'Body',
                    'type' => 'object',
                    'direction' => 0,
                    'default' => '{}',
                ]);
            }
        }

        return $block;
    }

    public function update(Block $block, array $data): Block
    {
        $block->update([
            'title' => $data['title'] ?? $block->title,
            'description' => $data['description'] ?? $block->description,
            'type' => $data['type'] ?? $block->type,
        ]);

//        if (isset($data['ports'])) {
//            $block->ports()->delete();
//            $block->ports()->createMany($data['ports']);
//        }

        return $block;
    }
}
