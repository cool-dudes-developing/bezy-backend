<?php

namespace App\Blocks;

class ObjectBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = [];

    public function run(): array
    {
        return [
            'Result' => $this->Properties ?? []
        ];
    }
}
