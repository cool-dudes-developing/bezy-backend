<?php

namespace App\Blocks;

class AcotBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['X'];

    public function run(): array
    {
        return [
            'Result' => atan(1 / $this->X)
        ];
    }
}
