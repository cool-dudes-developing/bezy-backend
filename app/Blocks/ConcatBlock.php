<?php

namespace App\Blocks;

class ConcatBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['Strings'];

    public function run(): array
    {
        return [
            'Result' => implode('', $this->Strings)
        ];
    }
}
