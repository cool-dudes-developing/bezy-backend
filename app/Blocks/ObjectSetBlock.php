<?php

namespace App\Blocks;

class ObjectSetBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['Property', 'Value'];

    public function run(): array
    {
        $obj = $this->Object ?? [];
        $obj[$this->Property] = $this->Value;
        return [
            'Result' => $obj
        ];
    }
}
