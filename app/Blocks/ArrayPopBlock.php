<?php

namespace App\Blocks;

class ArrayPopBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['Array'];

    public function run(): array
    {
        array_pop($this->Array);
        return [
            'Result' => $this->Array
        ];
    }
}
