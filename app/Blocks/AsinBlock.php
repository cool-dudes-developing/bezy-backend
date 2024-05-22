<?php

namespace App\Blocks;

class AsinBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['X'];

    public function run(): array
    {
        return [
            'Result' => asin($this->X)
        ];
    }
}
