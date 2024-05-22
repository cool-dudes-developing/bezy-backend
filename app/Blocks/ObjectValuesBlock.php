<?php

namespace App\Blocks;

class ObjectValuesBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['Object'];

    public function run(): array
    {
        return [
            'Result' => array_values($this->Object)
        ];
    }
}
