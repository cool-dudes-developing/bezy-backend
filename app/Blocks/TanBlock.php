<?php

namespace App\Blocks;

class TanBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['X'];

    public function run(): array
    {
        return [
            'Result' => tan($this->X)
        ];
    }
}
