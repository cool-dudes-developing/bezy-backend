<?php

namespace App\Blocks;

class ArraySliceBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['Array', 'Start', 'Length'];

    public function run(): array
    {
        return [
            'Result' => array_slice($this->Array, $this->Start, $this->Length)
        ];
    }
}
