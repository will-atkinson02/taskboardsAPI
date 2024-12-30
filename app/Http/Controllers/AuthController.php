<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $user = User::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials',
                'success' => false
            ], 401);
        }

        $token = $user->createToken($user->username. 'Auth-Token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'token_type' => 'Bearer',
            'token' => $token,
            'success' => true
        ]);
    }

    public function logout(Request $request): JsonResponse 
    {   
        $user = User::where('username', $request->username)->first();

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
            'email' => 'required',
            'password' => 'required'
        ]);

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]); 

        if ($user) {
            $token = $user->createToken($user->username. 'Auth-Token')->plainTextToken;

            return response()->json([
                'message' => 'Registration succesful',
                'token_type' => 'Bearer',
                'token' => $token,
                'success' => true
            ]);
        }

        return response()->json([
            'message' => 'Something went wrong',
            'success' => false
        ], 500);
    }
    public function getUser(string $username): JsonResponse
    {
        $user = User::where('username', $username)
                ->with(['taskboards' => function ($query) {
                    $query->orderBy('updated_at', 'desc'); // Sort taskboards by updated_at
                }])
                ->first();
        
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
