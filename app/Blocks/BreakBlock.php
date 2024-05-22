<?php

namespace App\Blocks;

class BreakBlock extends GenericBlock implements BlockInterface
{
    public function run() : array | int
    {
        return -1;
    }
}
