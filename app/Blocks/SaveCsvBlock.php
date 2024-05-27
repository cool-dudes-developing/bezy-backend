<?php

namespace App\Blocks;

class SaveCsvBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['Filename', 'Rows'];

    public function run(): array
    {
        $file = fopen($this->Filename, 'w');
        foreach ($this->Rows as $row) {
            fputcsv($file, $row, $this->Delimeter ?? ',');
        }
        fclose($file);

        return [];
    }
}
