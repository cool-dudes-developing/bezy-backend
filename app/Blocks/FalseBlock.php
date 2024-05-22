<?php

namespace App\Blocks;

class FalseBlock extends GenericBlock implements BlockInterface
{
    public function run(): array
    {
        return [
            'False' => false
        ];
    }
}
