<?php

namespace App\Blocks;

class Md5Block extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['String'];

    public function run(): array
    {
        return [
            'Result' => md5($this->String)
        ];
    }
}
