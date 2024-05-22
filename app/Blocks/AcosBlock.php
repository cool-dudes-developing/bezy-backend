<?php

namespace App\Blocks;

class AcosBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['X'];

    public function run(): array
    {
        return [
            'Result' => acos($this->X)
        ];
    }
}
