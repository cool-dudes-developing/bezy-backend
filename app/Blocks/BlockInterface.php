<?php

namespace App\Blocks;

interface BlockInterface
{
    public function run() : array | int;
}
