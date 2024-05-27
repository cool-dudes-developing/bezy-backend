<?php

namespace App\Blocks;

class FileSizeBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['Filename'];

    public function run(): array
    {

        return [
            'Result' => \Storage::size(
                implode(DIRECTORY_SEPARATOR, ['projects', $this->service->getProject()->id, $this->Filename])
            )
        ];
    }
}
