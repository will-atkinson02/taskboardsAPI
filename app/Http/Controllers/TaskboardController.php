<?php

namespace App\Http\Controllers;

use App\Models\Taskboard;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskboardController extends Controller
{
    public function createTaskboard(Request $request): JsonResponse 
    {
        $taskboard = new Taskboard();

        $taskboard->name = $request->name;
        $taskboard->user_id = $request->user_id;

        if ($taskboard->save()) {
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

    public function getTaskBoardData(int $taskboardId): JsonResponse
    {
        $taskboard = Taskboard::with(['stages.tasks'])->find($taskboardId);

        return response()->json([
            'message' =>  'Taskboard data retrieved',
            'success' => true,
            'data' => $taskboard
        ]);
    }
}
