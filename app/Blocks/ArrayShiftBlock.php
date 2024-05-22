<?php

namespace App\Blocks;

class ArrayShiftBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['Array'];

    public function run(): array
    {
        array_shift($this->Array);
        return [
            'Result' => $this->Array
        ];
    }
}
