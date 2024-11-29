<?php

namespace App\Http\Controllers;

use App\Models\AppUser;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $user = AppUser::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        $token = $user->createToken($user->username. 'Auth-Token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'token_type' => 'Bearer',
            'token' => $token
        ]);
    }

    public function logout(Request $request): JsonResponse 
    {
        $user = AppUser::where('username', $request->username)->first();

        if ($user->tokens()->delete()) {
            return response()->json([
                'message' => 'Logout succesful',
            ]);
        }

        return response()->json([
            'message' => 'Not found'
        ], 404); 
    }

    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $user = AppUser::create([
            'username' => $request->username,
            'password' => Hash::make($request->password)
        ]); 

        if ($user) {
            $token = $user->createToken($user->username. 'Auth-Token')->plainTextToken;

            return response()->json([
                'message' => 'Registration succesful',
                'token_type' => 'Bearer',
                'token' => $token
            ]);
        }

        return response()->json([
            'message' => 'Something went wrong',
            'success' => false
        ], 500); 
    }
    public function getUser(Request $request): JsonResponse
    {
        $user = AppUser::where('username', $request->username)->first();

        if ($user) {
            return response()->json([
                'message' => 'User retrieved',
                'data' => $user
            ]);
        }

        return response()->json([
            'message' => 'Not authenticated',
        ], 401); 
    }
}
