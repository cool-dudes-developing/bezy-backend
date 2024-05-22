<?php

namespace App\Blocks;

class SlugifyBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['String'];

    public function run(): array
    {
        return [
            'Result' => \Str::slug($this->String)
        ];
    }
}
