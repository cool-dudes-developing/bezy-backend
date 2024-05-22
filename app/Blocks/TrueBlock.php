<?php

namespace App\Blocks;

class TrueBlock extends GenericBlock implements BlockInterface
{
    public function run(): array
    {
        return [
            'True' => true
        ];
    }
}
