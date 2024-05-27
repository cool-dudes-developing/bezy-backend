<?php

namespace App\Blocks;

class OpenCsvBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['Filename'];

    public function run(): array
    {
        $file = fopen($this->Filename, 'r');
        $contents = [];
        while (($data = fgetcsv($file, null, $this->Delimeter ?? ',')) !== false) {
            $contents[] = $data;
        }
        fclose($file);

        return [
            'Rows' => $contents
        ];
    }
}
