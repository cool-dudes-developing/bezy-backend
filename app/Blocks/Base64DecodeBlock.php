<?php

namespace App\Blocks;

class Base64DecodeBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['String'];

    public function run(): array
    {
        return [
            'Result' => base64_decode($this->String)
        ];
    }
}
