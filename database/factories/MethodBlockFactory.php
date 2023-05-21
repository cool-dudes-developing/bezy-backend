<?php

namespace Database\Factories;

use App\Models\Block;
use App\Models\Method;
use App\Models\MethodBlock;
use Illuminate\Database\Eloquent\Factories\Factory;

class MethodBlockFactory extends Factory
{
    protected $model = MethodBlock::class;

    public function definition(): array
    {
        return [
            'block_id' => Block::factory(),
            'parent_id' => Block::factory(),
            'x' => $this->faker->randomNumber(),
            'y' => $this->faker->randomNumber(),
        ];
    }
}
