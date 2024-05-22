<?php

namespace App\Blocks;

class ObjectDeleteBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['Object', 'Property'];

    public function run(): array
    {
        unset($this->Object[$this->Property]);
        return [
            'Result' => $this->Object
        ];
    }
}
