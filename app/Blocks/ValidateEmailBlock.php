<?php

namespace App\Blocks;

class ValidateEmailBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['Email'];

    public function run(): array
    {
        return [
            'Result' => filter_var($this->Email, FILTER_VALIDATE_EMAIL) !== false
        ];
    }
}
