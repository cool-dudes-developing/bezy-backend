<?php

namespace App\Blocks;

class XorBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['A', 'B'];

    public function run(): array
    {
        return [
            'Result' => $this->A xor $this->B
        ];
    }
}
