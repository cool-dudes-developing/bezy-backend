<?php

namespace App\Blocks;

class ValidateSha1Block extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['SHA1'];

    public function run(): array
    {
        return [
            'Result' => preg_match('/^[0-9a-f]{40}$/', $this->SHA1) === 1
        ];
    }
}
