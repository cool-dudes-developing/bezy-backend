<?php

namespace Database\Factories;

use App\Models\Method;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class MethodFactory extends Factory
{
    protected $model = Method::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'project_id' => Project::factory(),
        ];
    }
}
