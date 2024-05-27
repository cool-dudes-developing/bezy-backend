<?php

namespace App\Blocks;

class Base64EncodeBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['String'];

    public function run(): array
    {
        return [
            'Result' => base64_encode($this->String)
        ];
    }
}
