<?php

namespace App\Blocks;

class LogBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['Message'];

    public function run() : array
    {
        logger()->info($this->Message);
        $this->service->output($this->Message);
        return [];
    }
}
