<?php

namespace App\Blocks;

class DumpBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['data'];

    public function run() : array
    {
//        dump($this->data);
        return [];
    }
}
