<?php

namespace App\Blocks;

class ObjectGetBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['Object', 'Property'];

    public function run(): array
    {
        return [
            'Result' => $this->Object[$this->Property]
        ];
    }
}
