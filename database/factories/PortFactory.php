<?php

namespace Database\Factories;

use App\Models\Block;
use App\Models\Port;
use Illuminate\Database\Eloquent\Factories\Factory;

class PortFactory extends Factory
{
    protected $model = Port::class;

    public function definition(): array
    {
        return [
            'block_id' => Block::factory(),
            'name' => $this->faker->name(),
            'type' => $this->faker->word(),
            'direction' => $this->faker->boolean(),
        ];
    }
}
