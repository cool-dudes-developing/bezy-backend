<?php

namespace App\Blocks;

class AbsBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['X'];

    public function run(): array
    {
        return [
            'Result' => abs($this->X)
        ];
    }
}
