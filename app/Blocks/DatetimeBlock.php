<?php

namespace App\Blocks;

class DatetimeBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = [];

    public function run(): array
    {
        return [
            'Result' => date('Y-m-d H:i:s', strtotime($this->Date ?? 'now'))
        ];
    }
}
