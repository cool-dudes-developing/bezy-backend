<?php

namespace App\Blocks;

class FileUrlBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['Filename'];

    public function run(): array
    {

        return [
            'Result' => \Storage::url(
                implode(DIRECTORY_SEPARATOR, ['projects', $this->service->getProject()->id, $this->Filename])
            )
        ];
    }
}
