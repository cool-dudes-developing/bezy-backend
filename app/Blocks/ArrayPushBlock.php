<?php

namespace App\Blocks;

class ArrayPushBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['Element'];

    public function run(): array
    {
        $arr = $this->Array ?? [];
        $arr[] = $this->Element;
        return [
            'Result' => $arr
        ];
    }
}
