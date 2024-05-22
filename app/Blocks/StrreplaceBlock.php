<?php

namespace App\Blocks;

class StrreplaceBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['String', 'Search', 'Replace'];

    public function run(): array
    {
        return [
            'Result' => str_replace($this->Search, $this->Replace, $this->String)
        ];
    }
}
