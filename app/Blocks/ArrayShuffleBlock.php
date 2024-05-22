<?php

namespace App\Blocks;

class ArrayShuffleBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['Array'];

    public function run(): array
    {
        return [
            'Result' => $this->shuffle($this->Array)
        ];
    }

    private function shuffle(array $array): array
    {
        shuffle($array);
        return $array;
    }
}
