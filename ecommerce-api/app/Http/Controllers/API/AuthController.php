<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    // REGISTER
    public function register(RegisterRequest $request)
    {
        $data = $this->authService->register($request->validated());

        return response()->json([
            "user"  => new UserResource($data['user']),
            "token" => $data['token'],
            "message" => "User registered successfully",
        ], 201);
    }

    // LOGIN
    public function login(LoginRequest $request)
    {
        $data = $this->authService->login($request->validated());

        if (! $data) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        return response()->json([
            "user"  => new UserResource($data['user']),
            "token" => $data['token'],
            "message" => "User login successfully",
        ]);
    }

    // LOGOUT
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }

    // PROFILE
    public function me(Request $request)
    {
        return new UserResource($request->user());
    }
}

