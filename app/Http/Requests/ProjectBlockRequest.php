<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectBlockRequest extends BaseFormRequest
{
    public function store(): array
    {
        return [
            'title' => ['required'],
            'description' => ['nullable'],
            'type' => ['nullable', 'in:method,endpoint'],
        ];
    }

    public function update(): array
    {
        return [
            'title' => ['required'],
            'description' => ['nullable'],
            'http_method' => ['nullable', 'in:GET,POST,PUT,PATCH,DELETE'],
            'uri' => ['nullable', 'string'],
        ];
    }
}
