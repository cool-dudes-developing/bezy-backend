<?php

namespace App\Blocks;

class ArrayLengthBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['Array'];

    public function run(): array
    {
        return [
            'Result' => count($this->Array)
        ];
    }
}
