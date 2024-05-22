<?php

namespace App\Blocks;

class NullBlock extends GenericBlock implements BlockInterface
{
    public function run(): array
    {
        return [
            'Null' => null
        ];
    }
}
