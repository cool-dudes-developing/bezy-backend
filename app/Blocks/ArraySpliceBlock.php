<?php

namespace App\Blocks;

class ArraySpliceBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['Array', 'Start', 'Length', 'Replacement'];

    public function run(): array
    {
        return [
            'Result' => array_splice($this->Array, $this->Start, $this->Length, $this->Replacement)
        ];
    }
}
