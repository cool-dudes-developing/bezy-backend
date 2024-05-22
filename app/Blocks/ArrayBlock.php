<?php

namespace App\Blocks;

class ArrayBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = [];

    public function run(): array
    {
        return [
            'Result' => $this->Items ?? []
        ];
    }
}
