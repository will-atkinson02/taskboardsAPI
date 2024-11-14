<?php

namespace App\Http\Controllers;

use App\Models\AppUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppUserController extends Controller
{
    public function createUser(Request $request): JsonResponse 
    {
        $appUser = new AppUser();

        $appUser->username = $request->username;
        $appUser->password = $request->password;

        if ($appUser->save()) {
            return response()->json([
                'message' =>  'User created',
                'success' => true
            ], 201);
        }

        return response()->json([
            'message' => 'There was an error',
            'success' => false
        ], 500);
    }

    public function
}
