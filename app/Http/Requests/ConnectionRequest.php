<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConnectionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'from_method_block_id' => ['required', 'exists:method_blocks,id'],
            'to_method_block_id' => ['required', 'exists:method_blocks,id'],
            'from_port_id' => ['required', 'exists:ports,id'],
            'to_port_id' => ['required', 'exists:ports,id'],
        ];
    }
}
