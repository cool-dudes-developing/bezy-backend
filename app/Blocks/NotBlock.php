<?php

namespace App\Blocks;

class NotBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['A'];

    public function run(): array
    {
        return [
            'Result' => !$this->A
        ];
    }
}
