<?php

namespace App\Blocks;

class LogBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['Message'];

    public function run() : array
    {
        $this->service->log($this->parameters['Message']);
        return [];
    }
}
