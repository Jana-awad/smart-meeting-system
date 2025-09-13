<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;


class AuthController extends Controller
{
    //
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        $token = JWTAuth::fromUser($user);
        return response()->json(compact('user', 'token'), 201);
    }
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = JWTAuth::user();
        return response()->json(compact('user', 'token'));
    }
    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json(['message' => 'Successfully logged out']);
    }
    public function index()
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $users = User::all();
        return response()->json($users, 200);
    }
    /**
 * Send a password reset link to the given email.
 */
public function forgotPassword(Request $request)
{
    $validator = Validator::make($request->all(), [
        'email' => 'required|email',
    ]);

    if ($validator->fails()) {
        return response()->json(['message' => $validator->errors()->first()], 422);
    }

    $status = Password::sendResetLink(
        $request->only('email')
    );

    if ($status === Password::RESET_LINK_SENT) {
        return response()->json(['message' => trans($status)], 200);
    }

    // return the error message (e.g. user not found)
    return response()->json(['message' => trans($status)], 400);
}

/**
 * Reset the password (consumes token).
 */
public function resetPassword(Request $request)
{
    $validator = Validator::make($request->all(), [
        'email' => 'required|email',
        'token' => 'required|string',
        'password' => 'required|string|confirmed|min:8',
    ]);

    if ($validator->fails()) {
        return response()->json(['message' => $validator->errors()->first()], 422);
    }

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
            $user->password = Hash::make($password);
            $user->setRememberToken(Str::random(60));
            $user->save();
        }
    );

    if ($status === Password::PASSWORD_RESET) {
        return response()->json(['message' => trans($status)], 200);
    }

    return response()->json(['message' => trans($status)], 400);
}

}
