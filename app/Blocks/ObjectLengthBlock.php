<?php

namespace App\Blocks;

class ObjectLengthBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['Object'];

    public function run(): array
    {
        return [
            'Result' => count($this->Object)
        ];
    }
}
