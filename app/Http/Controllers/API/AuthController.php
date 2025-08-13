<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    //
    public function register(Request $request){
        $validator=Validator::make($request->all(),[
            'name'=>'required|string|max:255',
            'email'=>'required|string|email|max:255|unique:users',
            'password'=>'required|string|min:6|confirmed',
        ]);
        if($validator->fails()){
            return response()->json($validator->errors(),422);

        }
        $user=User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
        ]);
        $token=JWTAuth::fromUser($user);
        return response()->json(compact('user', 'token'), 201);
    }
    public function login (Request $request)
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

}
