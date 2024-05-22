<?php

namespace App\Blocks;

class StrlenBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['String'];

    public function run(): array
    {
        return [
            'Result' => strlen($this->String)
        ];
    }
}
