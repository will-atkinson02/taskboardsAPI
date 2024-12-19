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

    public function updateTask(int $taskId, Request $request): JsonResponse
    {
        $task = Task::find($taskId);

        $task->position = $request->position ?? $task->position;
        $task->description = $request->has('description') ? $request->description : $task->description;
        $task->colour = $request->colour ?? $task->colour;
        $task->stage_id = $request->stage_id ?? $task->stage_id;

        if ($task->save()) {
            return response()->json([
                'message' => 'Task updated',
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
        $task = Task::where('name', $request->name)->get()->makeHidden(['name', 'description', 'colour', 'stage_id', 'created_at', 'updated_at']);

        return response()->json([
            'message' =>  'Task ID retrieved',
            'success' => true,
            'data' => $task
        ]);
    }
}
