<?php

namespace Database\Seeders;

use App\Models\Block;
use App\Services\BlockService;
use Illuminate\Database\Seeder;

class GenericBlocksSeeder extends Seeder
{
    public function __construct(
        private readonly BlockService $blockService,
    )
    {
    }

    public function run(): void
    {
        foreach (config('blocks') as $superGroup => $groups) {
            foreach ($groups as $group => $blocks) {
                foreach ($blocks as $name => $block) {
                    if ($instance = Block::firstWhere('name', $block['name']))
                        $this->blockService->update($instance, $block);
                    else
                        $this->blockService->save($block + [
                                'category' => "$superGroup/$group",
                            ]
                        );
                }
            }
        }
    }
}
