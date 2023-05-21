<?php

namespace App\Observers;

use App\Models\Block;
use App\Models\ProjectBlock;

class ProjectBlockObserver
{
    public function created(ProjectBlock $projectBlock): void
    {
        $projectBlock->block->methodBlocks()->create([
            'block_id' => Block::firstWhere('name', 'start')->id,
            'x' => 10,
            'y' => 10,
        ]);

        $projectBlock->block->methodBlocks()->create([
            'block_id' => Block::firstWhere('name', 'end')->id,
            'x' => 500,
            'y' => 10,
        ]);

        $projectBlock->block->ports()->createMany([
            [
                'name' => 'Out',
                'type' => 'flow',
                'direction' => 1
            ],
            [
                'name' => 'In',
                'type' => 'flow',
                'direction' => 0
            ],
        ]);
    }

    public function updated(ProjectBlock $projectBlock): void
    {
    }

    public function deleted(ProjectBlock $projectBlock): void
    {
    }

    public function restored(ProjectBlock $projectBlock): void
    {
    }

    public function forceDeleted(ProjectBlock $projectBlock): void
    {
    }
}
