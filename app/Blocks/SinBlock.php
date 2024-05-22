<?php

namespace App\Blocks;

class SinBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['X'];

    public function run(): array
    {
        return [
            'Result' => sin($this->X)
        ];
    }
}
