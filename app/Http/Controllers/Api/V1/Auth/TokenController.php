<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;

class TokenController extends Controller
{
    public function index()
    {
        return response()->json([
            'tokens' => auth()->user()->tokens,
        ]);
    }

    public function destroy($id)
    {
        $token = auth()->user()->tokens()->find($id);

        if (!$token) {
            return response()->json([
                'message' => 'Token not found',
            ], 404);
        }

        $token->delete();
        return response()->json([
            'message' => 'Token deleted successfully',
        ]);
    }
}
