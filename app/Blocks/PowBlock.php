<?php

namespace App\Blocks;

class PowBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['X', 'Power'];

    public function run(): array
    {
        return [
            'Result' => pow($this->X, $this->Power)
        ];
    }
}
