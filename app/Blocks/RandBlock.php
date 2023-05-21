<?php

namespace App\Blocks;

class RandBlock extends GenericBlock implements BlockInterface
{
    public function run() : array
    {
        return [
            'Result' => rand(1, 100)
        ];
    }
}
