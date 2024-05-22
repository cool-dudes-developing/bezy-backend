<?php

namespace App\Blocks;

class StrRepeatBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['String', 'Times'];

    public function run(): array
    {
        return [
            'Result' => str_repeat($this->String, $this->Times)
        ];
    }
}
