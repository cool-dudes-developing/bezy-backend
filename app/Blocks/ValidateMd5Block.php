<?php

namespace App\Blocks;

class ValidateMd5Block extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['MD5'];

    public function run(): array
    {
        return [
            'Result' => preg_match('/^[a-f0-9]{32}$/', $this->MD5) === 1
        ];
    }
}
