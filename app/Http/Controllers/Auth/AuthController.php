<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\AuthAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\registerRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class AuthController extends Controller
{
    // Register
    public function register(registerRequest $request): \Illuminate\Http\JsonResponse
    {
        $user = AuthAction::createUserWithGravatarProfile($request->email, $request->password);
        $user->addresses()->create(AuthAction::getAddress());

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => new UserResource($user),
        ], ResponseAlias::HTTP_CREATED);
    }

    // Login
    public function login(LoginRequest $request): \Illuminate\Http\JsonResponse
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Invalid login credentials'], ResponseAlias::HTTP_UNAUTHORIZED);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => new UserResource($user),
        ]);
    }

    // Logout
    public function logout(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Successfully logged out']);
    }

    // Get the authenticated User
    public function me(Request $request): \Illuminate\Http\JsonResponse
    {
        return response()->json(new UserResource($request->user()));
    }
}
