<?php

namespace App\Blocks;

class SaveFileBlock extends GenericBlock implements BlockInterface
{
    protected array $requiredParameters = ['Filename', 'Data'];

    public function run(): array
    {
        $project = $this->service->getProject();
        $path = implode(DIRECTORY_SEPARATOR, ['projects', $project->id, $this->Filename]);

        $dataString = is_string($this->Data) ? $this->Data : json_encode($this->Data);

        \Storage::put(
            $path,
            $dataString
        );

        return [];
    }
}
