<?php

namespace App\Blocks;

class Sha1Block extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['String'];

    public function run(): array
    {
        return [
            'Result' => sha1($this->String)
        ];
    }
}
