<?php

namespace App\Blocks;

class StrContainsBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['String', 'Search'];

    public function run(): array
    {
        return [
            'Result' => str_contains($this->String, $this->Search)
        ];
    }
}
