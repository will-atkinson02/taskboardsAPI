<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function createTask(Request $request): JsonResponse
    {
        $task = new Task();

        $task->name = $request->name;
        $task->stage_id = $request->stage_id;

        if ($task->save()) {
            return response()->json([
                'message' => 'Task created',
                'success' => true
            ], 201);
        }

        return response()->json([
            'message' => 'Something went wrong',
            'success' => false
        ], 500);
    }
}
