<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::latest()->paginate(5);
        return response(view('users.index', compact('users')));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return response(view('users.create'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
           // 'Id'=>['required','string'],
            'name'=> 'required',
            'email'=> 'required|email|unique:users,email',
            'password'=> 'required|min:6',
        ]);
        User::create($request->all());
        return response(
            redirect()->route('users.index')
                ->with('success', 'User created successfully.')
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return response(view('users.show', compact('user')));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return response(view('users.edit', compact('user')));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            //'Id'=>['required','string'],
            'name'=> 'required',
            'email'=> 'required|email|unique:users,email',
            'password'=> 'required|min:6',
        ]);
        $user->update($request->all());
        return response(
            redirect()->route('users.index')
                ->with('success', 'User updated successfully.')
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response(
            redirect()->route('users.index')
                ->with('success', 'User  successfully.')
        );
    }
}
