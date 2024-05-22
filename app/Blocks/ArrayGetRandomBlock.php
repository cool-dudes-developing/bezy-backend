<?php

namespace App\Blocks;

class ArrayGetRandomBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['Array'];

    public function run(): array
    {
        return [
            'Result' => $this->Array[array_rand($this->Array)]
        ];
    }
}
