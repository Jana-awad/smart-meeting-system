<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    public function index()
    {
        return UserResource::collection(User::all());
    }

    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        // Extract roles separately if provided
        $roles = $data['roles'] ?? [];

        // Remove roles from data to avoid mass assignment issue
        unset($data['roles']);

        $user = User::create($data);

         if (!empty($roles)) {
            $user->roles()->sync($roles);
        }

        return new UserResource($user);

    }


    public function show($id)
    {
        $user = User::findOrFail($id);
        return new UserResource($user);
    }

    public function update(UpdateUserRequest $request, $id)
    {
        $user = User::findOrFail($id);
        $data = $request->validated();

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $roles = $data['roles'] ?? null;
        unset($data['roles']);

        $user->update($data);

         if (is_array($roles)) {
            $user->roles()->sync($roles);
        }

        return new UserResource($user);

    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['message' => 'User deleted']);
    }

}
