<?php

namespace App\Blocks;

class RandStrBlock extends GenericBlock implements BlockInterface
{
    public function run(): array
    {
        return [
            'Result' => substr(
                str_shuffle(
                    str_repeat(
                        '0123456789abcdefghijklmnopqrstuvwxyz',
                        5)
                ),
                0,
                $this->Length ?? 5
            )
        ];
    }
}
