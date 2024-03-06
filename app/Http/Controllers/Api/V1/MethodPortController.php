<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\PortResource;
use App\Models\Block;
use App\Models\MethodBlock;
use Illuminate\Http\Request;

class MethodPortController extends Controller
{
    public function store(Request $request, Block $block)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:255', 'in:string,number,boolean,array,object'],
            'direction' => ['required', 'boolean'],
        ]);

        $port = $block->ports()->create($validated);

        return response()->json([
            'message' => 'Port created successfully',
            'data' => PortResource::make($port)
        ]);
    }
}
