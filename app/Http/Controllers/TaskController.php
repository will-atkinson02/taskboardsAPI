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
        $task->position = $request->position;
        $task->stage_id = $request->stage_id;

        if ($task->save()) {
            return response()->json([
                'message' => 'Task created',
                'success' => true,
                'taskId' => $task->id
            ], 201);
        }

        return response()->json([
            'message' => 'Something went wrong',
            'success' => false
        ], 500);
    }

    public function moveTask(int $taskId, Request $request): JsonResponse
    {
        $task = Task::find($taskId);

        $task->position = $request->position;
        $task->stage_id = $request->stage_id;

        if ($task->save()) {
            return response()->json([
                'message' => 'Task moved',
                'success' => true
            ], 201);
        }

        return response()->json([
            'message' => 'Something went  wrong',
            'success' => false
        ], 500);
    }

    public function getTaskIdByName(Request $request): JsonResponse
    {
        $task = Task::where('name', $request->name)->get()->makeHidden(['name', 'stage_id', 'created_at', 'updated_at']);

        return response()->json([
            'message' =>  'Task ID retrieved',
            'success' => true,
            'data' => $task
        ]);
    }
}
