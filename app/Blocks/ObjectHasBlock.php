<?php

namespace App\Blocks;

class ObjectHasBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['Object', 'Property'];

    public function run(): array
    {
        return [
            'Result' => array_key_exists($this->Property, $this->Object)
        ];
    }
}
