<?php

namespace App\Blocks;

class RoundDownBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['A'];

    public function run(): array
    {
        return [
            'Result' => round($this->A, 0, PHP_ROUND_HALF_DOWN)
        ];
    }
}
