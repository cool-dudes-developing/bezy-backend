<?php

namespace App\Blocks;

class SubstrBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['String', 'Start', 'Length'];

    public function run(): array
    {
        return [
            'Result' => substr($this->String, $this->Start, $this->Length)
        ];
    }
}
