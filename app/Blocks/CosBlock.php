<?php

namespace App\Blocks;

class CosBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['X'];

    public function run(): array
    {
        return [
            'Result' => cos($this->X)
        ];
    }
}
