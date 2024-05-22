<?php

namespace App\Blocks;

class ArrayGetBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['Array', 'Index'];

    public function run(): array
    {
        return [
            'Result' => $this->Array[$this->Index]
        ];
    }
}
