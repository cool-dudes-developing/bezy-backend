<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    public function forgot(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email']
        ]);

        $user = User::where('email', $request->email)->first();

        $token = Password::createToken($user);

        \Mail::to($user)->send(new \App\Mail\ResetPasswordMail($user, $token));

        return response()->json(['message' => 'Reset password link sent to your email.'], 200);
    }
}
