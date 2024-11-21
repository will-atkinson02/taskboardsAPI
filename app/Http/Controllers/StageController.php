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
                'success' => true,
                'stageId' => $stage->id
            ], 201);
        }

        return response()->json([
            'message' => 'Something went wrong',
            'success' => false
        ], 500);
    }

    public function deleteStage(int $stageId): JsonResponse
    {
        $stage = Stage::find($stageId);

        if (!$stage) {
            return response()->json([
                'message' => 'Invalid stage id',
                'success' => false
            ], 404);
        }

        if ($stage->delete()) {
            return response()->json([
                'message' => 'Stage deleted',
                'success' => true
            ]);
        }
    }
}
