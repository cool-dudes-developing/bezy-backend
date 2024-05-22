<?php

namespace App\Blocks;

class CotBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['X'];

    public function run(): array
    {
        return [
            'Result' => 1 / tan($this->X)
        ];
    }
}
