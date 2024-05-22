<?php

namespace App\Blocks;

class DivideBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['A', 'B'];

    public function run(): array
    {
        return [
            'Result' => $this->A / $this->B
        ];
    }
}
