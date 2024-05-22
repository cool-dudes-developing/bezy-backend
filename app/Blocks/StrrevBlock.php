<?php

namespace App\Blocks;

class StrrevBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['String'];

    public function run(): array
    {
        return [
            'Result' => strrev($this->String)
        ];
    }
}
