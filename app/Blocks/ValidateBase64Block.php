<?php

namespace App\Blocks;

use Carbon\Carbon;

class ValidateBase64Block extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['Base64'];

    public function run(): array
    {
        return [
            'Result' => base64_decode($this->Base64) !== false
        ];
    }
}
