<?php

namespace App\Blocks;

class GetBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['Variable', 'Value'];

    public function run() : array | int
    {
        return [
            'Value' => $this->service->getVariable($this->Variable)
        ];
    }
}
