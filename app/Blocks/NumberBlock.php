<?php

namespace App\Blocks;

class NumberBlock extends GenericBlock implements BlockInterface
{
    public function run(): array
    {
        return [
            'Number' => $this->parameters['_constant']
        ];
    }
}
