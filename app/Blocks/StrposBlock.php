<?php

namespace App\Blocks;

class StrposBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['String', 'Search'];

    public function run(): array
    {
        return [
            'Result' => strpos($this->String, $this->Search)
        ];
    }
}
