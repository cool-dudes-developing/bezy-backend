<?php

namespace App\Blocks;

class ArrayUnshiftBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['Array', 'Elements'];

    public function run(): array
    {
        array_unshift($this->Array, ...$this->Elements);
        return [
            'Result' => $this->Array
        ];
    }
}
