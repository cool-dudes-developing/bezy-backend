<?php

namespace App\Blocks;

class StrtoupperBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['String'];

    public function run(): array
    {
        return [
            'Result' => strtoupper($this->String)
        ];
    }
}
