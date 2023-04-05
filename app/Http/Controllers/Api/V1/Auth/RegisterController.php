<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;

class RegisterController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();
        $validated['password'] = bcrypt($validated['password']);
        $user = User::create($validated);

        $device_name = request('device_name') ?? request()->header('User-Agent');

        return response()->json([
            'user' => $user,
            'access_token' => $user->createToken($device_name)->accessToken,
        ]);
    }
}
