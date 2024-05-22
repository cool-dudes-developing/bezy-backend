<?php

namespace App\Blocks;

class TrimBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['String'];

    public function run(): array
    {
        return [
            'Result' => trim($this->String)
        ];
    }
}
