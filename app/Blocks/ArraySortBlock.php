<?php

namespace App\Blocks;

class ArraySortBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['Array'];

    public function run(): array
    {
        return [
            'Result' => sort($this->Array)
        ];
    }
}
