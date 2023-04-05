<?php

namespace Database\Factories;

use App\Models\ConnectedPort;
use App\Models\MethodBlock;
use App\Models\Port;
use Illuminate\Database\Eloquent\Factories\Factory;

class ConnectedPortFactory extends Factory
{
    protected $model = ConnectedPort::class;

    public function definition(): array
    {
        return [
            'method_block_id' => MethodBlock::factory(),
            'port_id' => Port::factory(),
            'connected_to' => Port::factory(),
        ];
    }
}
