<?php

namespace App\Blocks;

use App\Blocks\BlockInterface;
use App\Blocks\GenericBlock;

class OneBlock extends GenericBlock implements BlockInterface
{
    public function run(): array
    {
        return [
            'One' => 1
        ];
    }
}
