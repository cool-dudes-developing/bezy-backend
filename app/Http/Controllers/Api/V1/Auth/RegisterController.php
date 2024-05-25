<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();

        $validated['password'] = bcrypt($validated['password']);
        if ($user = User::where('email', $validated['email'])->first()) {
            if ($user->password) {
                throw ValidationException::withMessages([
                    'email' => 'Email already registered',
                ]);
            }
            $user = User::where('email', $validated['email'])->first();
            $user->update($validated);
        } else {
            $user = User::create($validated);
        }


        $device_name = request('device_name') ?? request()->header('User-Agent');

        return response()->json([
            'user' => $user,
            'access_token' => $user->createToken($device_name)->accessToken,
        ]);
    }
}
