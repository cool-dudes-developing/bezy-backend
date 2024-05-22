<?php

namespace App\Blocks;

class ArrayReverseBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['Array'];

    public function run(): array
    {
        return [
            'Result' => array_reverse($this->Array)
        ];
    }
}
