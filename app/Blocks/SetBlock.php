<?php

namespace App\Blocks;

class SetBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['Variable', 'Value'];

    public function run() : array | int
    {
        $this->service->setVariable($this->Variable, $this->Value);
        return [];
    }
}
