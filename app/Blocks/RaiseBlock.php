<?php

namespace App\Blocks;

use Exception;

class RaiseBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['Error'];

    /**
     * @throws Exception
     */
    public function run(): array
    {
        throw new Exception($this->parameters['Error'] ?? 'An error occurred');
    }
}
