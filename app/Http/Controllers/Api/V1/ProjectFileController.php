<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Project;

class ProjectFileController
{
    public function index(Project $project)
    {
        return response()->json(
            collect(\Storage::files(implode(DIRECTORY_SEPARATOR, ['projects', $project->id])))->map(function ($file) {
                return [
                    'name' => basename($file),
                    'path' => $file,
                    'type' => \Storage::mimeType($file)
                ];
            })
        );
    }

    public function store(Project $project)
    {
        request()->validate([
            'path' => ['nullable', 'string'],
            'files' => ['required', 'array'],
            'files.*' => ['required', 'file'],
            'force' => ['nullable', 'boolean']
        ]);

        if (!request('force')) {
            // check if any of the files already exists
            $existing = collect(\Storage::files(implode(DIRECTORY_SEPARATOR, ['projects', $project->id, request('path')])))
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
                    implode(DIRECTORY_SEPARATOR, ['projects', $project->id, request('path')]),
                    $file->getClientOriginalName()
                ),
                'type' => $file->getClientMimeType()
            ];
        }

        return response()->json($saved, 201);
    }
}
