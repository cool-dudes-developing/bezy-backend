<?php

namespace App\Blocks;

class AtanBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['X'];

    public function run(): array
    {
        return [
            'Result' => atan($this->X)
        ];
    }
}
