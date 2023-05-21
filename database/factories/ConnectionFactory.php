<?php

namespace Database\Factories;

use App\Models\Connection;
use App\Models\MethodBlock;
use App\Models\Port;
use Illuminate\Database\Eloquent\Factories\Factory;

class ConnectionFactory extends Factory
{
    protected $model = Connection::class;

    public function definition(): array
    {
        return [
            'from_method_block_id' => MethodBlock::factory(),
            'from_port_id' => Port::factory(),
            'to_method_block_id' => MethodBlock::factory(),
            'to_port_id' => Port::factory(),
        ];
    }
}
