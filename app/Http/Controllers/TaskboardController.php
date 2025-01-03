<?php

namespace App\Http\Controllers;

use App\Models\Taskboard;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskboardController extends Controller
{
    public function createTaskboard(Request $request): JsonResponse 
    {   
        $request->validate([
            'name' => 'string|min:1|max:20',
            'user_id' => 'int|min:0|exists:users,id'
        ]);

        $user_id = User::where('username', $request->username)->value('id');

        $nameCheck = Taskboard::where('user_id', $user_id)
                        ->where('name', 'LIKE', 'Untitled %')
                        ->whereRaw('name REGEXP ?', ['^Untitled [0-9]+$'])
                        ->orderByRaw('CAST(SUBSTRING(name, 9) AS UNSIGNED) DESC')
                        ->value('name');

        $taskboard = new Taskboard();

        if (!$nameCheck) {
            $taskboard->name = "Untitled 1";    
        } else {
            $taskboard->name = "Untitled ". (String)((int)substr($nameCheck, 9) + 1);
        }
        $taskboard->user_id = $user_id;

        if ($taskboard->save()) {
            return response()->json([
                'message' =>  'Taskboard created',
                'success' => true,
                'taskboard_id' => $taskboard->id
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
            'message' =>  'Taskboard retrieved',
            'success' => true,
            'data' => $taskboard
        ]);
    }

    public function updateTaskboardName(int $taskboardId, Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'string|min:1|max:20',
        ]);

        $taskboard = Taskboard::find($taskboardId);

        if (!$taskboard) {
            return response()->json([
                'message' => 'Invalid taskboard id',
                'success' => false
            ], 404);
        }

        $taskboard->name = $request->name;

        if ($taskboard->save()) {
            return response()->json([
                'message' =>  'Taskboard updated',
                'success' => true
            ], 201);
        }

        return response()->json([
            'message' => 'Something went wrong',
            'success' => false
        ], 500);
    }

    public function deleteTaskboard(int $taskboardId): JsonResponse
    {
        $taskboard = Taskboard::find($taskboardId);

        if (!$taskboard) {
            return response()->json([
                'message' => 'Invalid taskboard id',
                'success' => false
            ], 404);
        }
        
        $taskboard->stages()->each(function ($stage) {
            $stage->tasks()->delete();
        });

        $taskboard->stages()->delete();

        if ($taskboard->delete()) {
            return response()->json([
                'message' => 'Taskboard deleted',
                'success' => true
            ]);
        }

        return response()->json([
            'message' => 'Something went wrong',
            'success' => false
        ], 500);
    }
}
