<?php

namespace App\Blocks;

class ObjectSetBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['Object', 'Property', 'Value'];

    public function run(): array
    {
        $this->Object[$this->Property] = $this->Value;
        return [
            'Result' => $this->Object
        ];
    }
}
