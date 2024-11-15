<?php

namespace App\Http\Controllers;

use App\Models\Stage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StageController extends Controller
{
    public function createStage(Request $request): JsonResponse 
    {
        $stage = new Stage();

        $stage->name = $request->name;
        $stage->taskboard_id = $request->taskboard_id;

        if ($stage->save()) {
            return response()->json([
                'message' =>  'Taskboard created',
                'success' => true
            ], 201);
        }

        return response()->json([
            'message' => 'Something went wrong',
            'success' => false
        ], 500);
    }
}
