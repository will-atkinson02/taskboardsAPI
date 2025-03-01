<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function createTask(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|min:1|max:255',
            'position' => 'required|int|min:0',
            'stage_id' => 'required|int|min:0|exists:stages,id'
        ]);

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
        $request->validate([
            'name' => 'string|min:1',
            'position' => 'int|min:0',
            'description' => 'string|min:0|max:255',
            'colour' => 'string|in:red,blue,green,yellow,orange,purple',
            'stage_id' => 'int|min:0|exists:stages,id'
        ]);

        $task = Task::find($taskId);

        $task->name = $request->name ?? $task->name;
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

    public function deleteTask(int $taskId): JsonResponse
    {
        $task = Task::find($taskId);

        if (!$task) {
            return response()->json([
                'message' => 'Invalid task id',
                'success' => false
            ], 404);
        }

        if ($task->delete()) {
            return response()->json([
                'message' => 'Task deleted',
                'success' => true
            ]);
        }

        return response()->json([
            'message' => 'Server error',
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
