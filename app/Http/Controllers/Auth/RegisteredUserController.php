<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
/**
* Handle an incoming registration request.
*
* @throws \Illuminate\Validation\ValidationException
*/
public function store(Request $request): JsonResponse
{
// Validate the input
$request->validate([
'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
'password' => ['required', Rules\Password::defaults()],
]);

// Create the user
$username = explode('@', $request->email)[0];
$user = User::create([
'name' => $username,
'email' => $request->email,
'password' => Hash::make($request->password),
]);



// Create an API token
$token = $user->createToken('token')->plainTextToken;

// Return the response with the token and user data (optional)
return response()->json([
'success' => 'Registration successful',
'token' => $token,
'user' => $user,  // Optionally return the user data
]);
}
}
