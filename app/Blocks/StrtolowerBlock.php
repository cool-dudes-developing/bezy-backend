<?php

namespace App\Blocks;

class StrtolowerBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['String'];

    public function run(): array
    {
        return [
            'Result' => strtolower($this->String)
        ];
    }
}
