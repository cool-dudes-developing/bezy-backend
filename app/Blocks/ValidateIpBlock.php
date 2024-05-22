<?php

namespace App\Blocks;

class ValidateIpBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['IP'];

    public function run(): array
    {
        return [
            'Result' => filter_var($this->IP, FILTER_VALIDATE_IP) !== false
        ];
    }
}
