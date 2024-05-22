<?php

namespace App\Blocks;

class Atan2Block extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['Y', 'X'];

    public function run(): array
    {
        return [
            'Result' => atan2($this->Y, $this->X)
        ];
    }
}
