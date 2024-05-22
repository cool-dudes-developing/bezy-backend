<?php

namespace App\Blocks;

class RandBlock extends GenericBlock implements BlockInterface
{
    public function run() : array
    {
        return [
            'Result' => rand(
                $this->Min ?? 0,
                $this->Max ?? 100)
        ];
    }
}
