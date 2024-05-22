<?php

namespace App\Blocks;

class ArraySetBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['Array', 'Index', 'Value'];

    public function run(): array
    {
        $this->Array[$this->Index] = $this->Value;
        return [
            'Result' => $this->Array
        ];
    }
}
