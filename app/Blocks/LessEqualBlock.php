<?php

namespace App\Blocks;

class LessEqualBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['A', 'B'];

    public function run() : array | int
    {
        return [
            'Result' => $this->A <= $this->B
        ];
    }
}
