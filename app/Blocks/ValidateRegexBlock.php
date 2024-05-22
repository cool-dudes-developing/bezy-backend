<?php

namespace App\Blocks;

class ValidateRegexBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['String', 'Pattern'];

    public function run(): array
    {
        return [
            'Result' => preg_match($this->Pattern, $this->String) === 1
        ];
    }
}
