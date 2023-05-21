<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectBlockRequest extends BaseFormRequest
{
    public function store(): array
    {
        return [
            'name' => ['required'],
            'description' => ['nullable'],
        ];
    }

    public function update(): array
    {
        return [
            'name' => ['required'],
            'description' => ['nullable'],
        ];
    }
}
