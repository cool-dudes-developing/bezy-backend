<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Block;
use App\Models\Project;
use App\Services\BlockService;
use App\Services\MethodBlockService;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function __construct(
        protected BlockService       $blockService,
        protected MethodBlockService $methodBlockService
    )
    {
    }

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        foreach (config('blocks') as $block) {
            $this->blockService->save($block);
        }

        $user = \App\Models\User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $project = $user->projects()->create([
            'slug' => 'weather-app',
            'name' => 'Weather App',
            'description' => 'A simple weather app',
        ]);

        $block = $this->blockService->save(
            [
                'title' => 'Get Weather',
                'description' => 'Get the weather for a given location',
                'ports' => [
                    [
                        'name' => 'location',
                        'type' => 'string',
                        'direction' => 0
                    ],
                    [
                        'name' => 'temperature',
                        'type' => 'number',
                        'direction' => 1
                    ],
                ],
            ],
        );

        $project->methods()->attach($block);

        $start = $block->methodBlocks()->firstOrFail();

        $log = $this->methodBlockService->save(
            $block,
            [
                'block_id' => Block::firstWhere('name', 'log')->id,
                'x' => 250,
                'y' => 250,
            ]
        );

        $this->methodBlockService->connect(
            $start,
            $log,
            $start->ports()->firstWhere('name', 'location'),
            $log->ports()->firstWhere('name', 'message')
        );

        $this->methodBlockService->connect(
            $start,
            $log,
            $start->ports()->firstWhere('name', 'In'),
            $log->ports()->firstWhere('name', 'In')
        );
    }
}
