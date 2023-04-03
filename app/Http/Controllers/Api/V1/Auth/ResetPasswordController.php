<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Password;

class ResetPasswordController extends Controller
{
    public function reset()
    {
        $credentials = request(['email', 'password', 'password_confirmation', 'token']);

        $response = Password::reset($credentials, function (User $user, $password) {
            $user->password = bcrypt($password);
            $user->save();

            // Delete all tokens
            // user need to login again after password reset
            // TODO: add support for api keys, because they should not be deleted
            $user->tokens()->delete();
        });

        if ($response == Password::PASSWORD_RESET) {
            return response()->json(['message' => 'Password reset successfully.'], 200);
        }

        return response()->json(['error' => 'Password reset failed.'], 400);
    }
}
