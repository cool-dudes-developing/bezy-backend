<?php

namespace App\Http\Requests;

class ProjectRequest extends BaseFormRequest
{
    public function store(): array
    {
        return [
            'name' => ['required', 'max:255'],
            'description' => ['required'],
            'slug' => ['sometimes', 'max:255', 'unique:projects'],
        ];
    }

    public function update(): array
    {
        return [
            'name' => ['sometimes', 'max:255'],
            'description' => ['sometimes'],
            'slug' => ['sometimes', 'max:255', 'unique:projects,slug,' . $this->route('project')->id],
        ];
    }
}
