<?php

namespace App\Blocks;

class ValidateUrlBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['URL'];

    public function run(): array
    {
        return [
            'Result' => filter_var($this->URL, FILTER_VALIDATE_URL) !== false
        ];
    }
}
