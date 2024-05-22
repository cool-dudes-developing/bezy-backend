<?php

namespace App\Blocks;

class IsnullBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['A'];

    public function run() : array | int
    {
        return [
            'Result' => is_null($this->A)
        ];
    }
}
