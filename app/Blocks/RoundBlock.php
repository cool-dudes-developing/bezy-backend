<?php

namespace App\Blocks;

class RoundBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['A'];

    public function run(): array
    {
        return [
            'Result' => round($this->A)
        ];
    }
}
