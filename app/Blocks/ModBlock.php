<?php

namespace App\Blocks;

class ModBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['Divident', 'Divisor'];

    public function run(): array
    {
        return [
            'Remainder' => $this->Divident % $this->Divisor,
            'Result' => floor($this->Divident / $this->Divisor)
        ];
    }
}
