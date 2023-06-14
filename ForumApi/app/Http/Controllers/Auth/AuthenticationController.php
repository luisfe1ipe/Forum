<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthenticationController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);
        $token = $user->createToken("forumapp")->plainTextToken;

        return response([
            'user' => $user,
            'token' => $token
        ], 201);
    }

    public function login(LoginRequest $request)
    {
        $data = $request->validated();

        $user = User::whereUsername($data['username'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response([
                'message' => 'Invalid credentials',
            ], 422);
        }

        $token = $user->createToken("forumapp")->plainTextToken;

        return response([
            'user' => $user,
            'token' => $token
        ], 200);
    }
}
