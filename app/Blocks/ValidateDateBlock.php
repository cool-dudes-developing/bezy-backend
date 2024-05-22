<?php

namespace App\Blocks;

use Carbon\Carbon;

class ValidateDateBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['Date', 'Format'];

    public function run(): array
    {
        return [
            'Result' => Carbon::createFromFormat($this->Format, $this->Date)->format($this->Format) === $this->Date
        ];
    }
}
