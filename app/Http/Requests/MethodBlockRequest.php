<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MethodBlockRequest extends BaseFormRequest
{
    public function store(): array
    {
        return [
            'x' => ['required', 'integer'],
            'y' => ['required', 'integer'],
            'block_id' => ['required', 'string', 'exists:blocks,id'],
            'constant' => ['nullable'],
        ];
    }

    public function update(): array
    {
        return [
            'x' => ['required', 'integer'],
            'y' => ['required', 'integer'],
            'constant' => ['nullable'],
        ];
    }
}
