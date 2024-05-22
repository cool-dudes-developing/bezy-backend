<?php

namespace App\Blocks;

use Carbon\Carbon;

class ValidateJsonBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['JSON'];

    public function run(): array
    {
        return [
            'Result' => json_decode($this->JSON) !== null
        ];
    }
}
