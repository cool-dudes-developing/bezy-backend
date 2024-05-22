<?php

namespace App\Blocks;

class ZeroBlock extends GenericBlock implements BlockInterface
{
    public function run(): array
    {
        return [
            'Zero' => 0
        ];
    }
}
