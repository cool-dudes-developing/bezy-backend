<?php

namespace Database\Factories;

use App\Models\Block;
use App\Models\Project;
use App\Models\ProjectBlock;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectBlockFactory extends Factory
{
    protected $model = ProjectBlock::class;

    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'block_id' => Block::factory(),
        ];
    }
}
