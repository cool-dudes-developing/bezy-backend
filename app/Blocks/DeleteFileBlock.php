<?php

namespace App\Blocks;

class DeleteFileBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['Filename'];

    public function run(): array
    {
        $project = $this->service->getProject();
        $path = implode(DIRECTORY_SEPARATOR, ['projects', $project->id, $this->Filename]);

        \Storage::delete($path);

        return [];
    }
}
