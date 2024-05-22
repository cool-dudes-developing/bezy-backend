<?php

namespace App\Blocks;

class StringBlock extends GenericBlock implements BlockInterface
{
    public function run(): array
    {
        return [
            'String' => $this->parameters['_constant']
        ];
    }
}
