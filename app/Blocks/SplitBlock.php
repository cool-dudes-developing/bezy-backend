<?php

namespace App\Blocks;

class SplitBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['String', 'Delimiter'];

    public function run(): array
    {
        return [
            'Result' => explode($this->Delimiter, $this->String)
        ];
    }
}
