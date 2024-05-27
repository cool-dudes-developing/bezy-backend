<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Project;

class ProjectFileController
{
    public function index(Project $project)
    {
        $path = request('path') ? implode(DIRECTORY_SEPARATOR, ['projects', $project->id, request('path')]) : implode(DIRECTORY_SEPARATOR, ['projects', $project->id]);
        return response()->json(
            [
                'files' => collect(\Storage::files($path))->map(function ($file) {
                    return [
                        'name' => basename($file),
                        'path' => $file,
                        'type' => \Storage::mimeType($file)
                    ];
                }),
                'directories' => collect(\Storage::directories($path))->map(function ($directory) {
                    return [
                        'name' => basename($directory),
                        'path' => $directory,
                        'type' => 'directory'
                    ];
                })
            ]
        );
    }

    private function createDirectory($path, $name, $index = 1)
    {
        $tempName = $index > 1 ? $name . ' (' . $index . ')' : $name;
        if (\Storage::exists(implode(DIRECTORY_SEPARATOR, [$path, $tempName]))) {
            return $this->createDirectory($path, $name, $index + 1);
        }
        \Storage::makeDirectory(implode(DIRECTORY_SEPARATOR, [$path, $tempName]));
        return $tempName;
    }

    public function store(Project $project)
    {
        $validated = request()->validate([
            'path' => ['nullable', 'string'],
            'name' => ['nullable', 'string', 'required_without:files'],
            'files' => ['required_without:name', 'array'],
            'files.*' => ['required_with:files', 'file'],
            'force' => ['nullable', 'boolean'],
        ]);

        $path = implode(DIRECTORY_SEPARATOR, ['projects', $project->id, request('path')]);

        if (isset($validated['files'])) {

            if (!request('force')) {
                // check if any of the files already exists
                $existing = collect(\Storage::files($path))
                    ->map(function ($file) {
                        return basename($file);
                    });
                $files = collect(request()->file('files'))->map(function ($file) {
                    return $file->getClientOriginalName();
                });

                if ($existing->intersect($files)->isNotEmpty()) {
                    return response()->json([
                        'type' => 'exists',
                        'message' => 'Some files already exist.'
                    ], 409);
                }
            }

            $saved = [];
            foreach (request()->file('files') as $file) {
                $saved[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $file->storeAs(
                        $path,
                        $file->getClientOriginalName()
                    ),
                    'type' => $file->getClientMimeType()
                ];
            }

            return response()->json($saved, 201);
        } else {
            // create folder
            $name = $this->createDirectory($path, $validated['name']);
            return response()->json([
                'name' => $name,
                'path' => $path . DIRECTORY_SEPARATOR . $name,
                'type' => 'directory'
            ], 201);
        }
    }

    public function update(Project $project, string $name)
    {
        $validated = request()->validate([
            'path' => ['required', 'string'],
            'name' => ['required', 'string'],
        ]);

        $split = explode(DIRECTORY_SEPARATOR, $validated['path']);
        $filename = array_pop($split);
        $path = implode(DIRECTORY_SEPARATOR, $split);

        $oldPath = implode(DIRECTORY_SEPARATOR, [$path, $filename]);
        $newPath = implode(DIRECTORY_SEPARATOR, [$path, $validated['name']]);

        if (\Storage::exists($oldPath) || \Storage::directoryExists($oldPath)) {
            \Storage::move($oldPath, $newPath);
            return response()->json([
                'name' => $validated['name'],
                'path' => $newPath,
                'type' => \Storage::directoryExists($newPath) ? 'directory' : \Storage::mimeType($newPath)
            ]);
        }

        return response()->json([
            'message' => 'File not found. '. $oldPath
        ], 404);
    }
}
