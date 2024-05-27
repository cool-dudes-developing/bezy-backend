<?php

namespace App\Blocks;

class OpenFileBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['Filename'];

    public function run(): array
    {
        $file = fopen($this->Filename, 'r');
        $contents = fread($file, filesize($this->Filename));
        fclose($file);

        return [
            'Result' => $contents
        ];
    }
}
