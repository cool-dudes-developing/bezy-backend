<?php

namespace App\Blocks;

class ArraySortByBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['Array', 'Key'];

    public function run(): array
    {
        return [
            'Result' => $this->sortBy($this->Array, $this->Key)
        ];
    }

    private function sortBy(array $array, string $key): array
    {
        usort($array, function ($a, $b) use ($key) {
            return $a[$key] <=> $b[$key];
        });

        return $array;
    }
}
