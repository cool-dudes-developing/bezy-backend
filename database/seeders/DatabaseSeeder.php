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
    public function __construct()
    {
    }

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            GenericBlocksSeeder::class,
        ]);
    }
}
