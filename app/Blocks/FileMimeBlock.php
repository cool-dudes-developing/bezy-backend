<?php

namespace App\Blocks;

class FileMimeBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['Filename'];

    public function run(): array
    {
        return [
            'Result' => \Storage::mimeType(
                implode(DIRECTORY_SEPARATOR, ['projects', $this->service->getProject()->id, $this->Filename])
            )
        ];
    }
}
