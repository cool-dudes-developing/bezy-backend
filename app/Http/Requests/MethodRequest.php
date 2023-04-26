<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MethodRequest extends BaseFormRequest
{
    public function store(): array
    {
        return [
            'name' => ['required'],
        ];
    }

    public function update(): array
    {
        return [
            'name' => ['required'],
        ];
    }
}
