<?php

namespace App\Blocks;

class DatetimeFormatBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['Datetime', 'Format'];

    public function run(): array
    {
        return [
            'Result' => date($this->Format, strtotime($this->Datetime))
        ];
    }
}
