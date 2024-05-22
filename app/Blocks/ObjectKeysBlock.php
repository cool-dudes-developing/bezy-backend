<?php

namespace App\Blocks;

class ObjectKeysBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['Object'];

    public function run(): array
    {
        return [
            'Result' => array_keys($this->Object)
        ];
    }
}
