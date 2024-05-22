<?php

namespace App\Blocks;

class SqrtBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['X'];

    public function run(): array
    {
        return [
            'Result' => sqrt($this->X)
        ];
    }
}
